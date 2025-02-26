<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Product;
use App\Models\WorkOrder;
use App\Models\WorkOrderProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Work Order List';

        $workorders = WorkOrder::with('products')->OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.workorder.index',compact('pageTitle','workorders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $clients = Client::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Work Order';

        // Generate a random 8-digit number
        $randomNumber = mt_rand(100000, 999999);

        $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.workorder.create',compact('pageTitle', 'clients', 'products','invoice_no')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'client' => 'required|exists:clients,id',
            'invoice_no' => 'required|unique:work_orders,invoice_no',
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required|not_in:',  // Ensure at least one product is selected
        ]);


        // dd($validated);

        // Access product data from the request
        $productIds = explode(',', $request->input('product_ids'));  // Array of product IDs
        $quantities = explode(',', $request->input('quantities'));  // Array of quantities
        $prices = explode(',', $request->input('prices'));  // Array of prices

        // Check if at least one product is selected
        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            // If no product is selected, return an error message
            return back()->with('error', 'At least one product must be selected.');
        }

      

        try {
            // Start the transaction
            \DB::beginTransaction();

            // Create a new WorkOrder record
            $WorkOrder = new WorkOrder();
            $WorkOrder->client_id = $validated['client'];
            $WorkOrder->invoice_no = $validated['invoice_no'];
            $WorkOrder->invoice_date = $validated['invoice_date'];
            $WorkOrder->subtotal = $validated['subtotal'];
            $WorkOrder->discount = $validated['discount'];
            $WorkOrder->total = $validated['total'];
            $WorkOrder->save();

            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $quantities[$index];
                $price = $prices[$index];

                // Insert into WorkOrderProduct table
                $WorkOrderProduct = new WorkOrderProduct();
                $WorkOrderProduct->work_order_id = $WorkOrder->id; // Link to the sale
                $WorkOrderProduct->product_id = $productId; // Product ID
                $WorkOrderProduct->quantity = $quantity; // Quantity
                $WorkOrderProduct->price = $price; // Price
                $WorkOrderProduct->save(); // Save the record
            }

            // Commit the transaction
            \DB::commit();

            // Redirect back with a success message
            return redirect()->route('workorders.index')->with('success', 'Work Order created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            \DB::rollback();

            // Log the error message
            Log::error('Work Orders creation failed: ', [
                'error' => $e->getMessage(),
                'exception' => $e,
                'user_id' => auth()->id(),  // Optional: Log user ID if you're tracking who made the request
                'data' => $validated,  // Optional: Log the validated data for debugging purposes
            ]);

            // Return with the actual error message to be displayed on the front end
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workorder = WorkOrder::where('id', $id)
            ->with(['products', 'client']) // Include client details
            ->first();
        $pageTitle = 'WorkOrder View';

        return view('backend.admin.inventory.workorder.view',compact('pageTitle', 'workorder'));

    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Work Order Edit';

        $workorder = WorkOrder::where('id', $id)
            ->with(['products', 'client']) // Include client details
            ->first();
        
        if ($workorder->invoice_date) {
            $workorder->invoice_date = Carbon::parse($workorder->invoice_date);
        }

        $subtotal = $workorder->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });

        $clients = Client::orderBy('id', 'desc')->get();
        $products = Product::where('status',1)->latest()->get();

        return view('backend.admin.inventory.workorder.edit',compact('pageTitle', 'workorder', 'clients', 'products', 'subtotal')); 
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());

        // Validate Request
        $validated = $request->validate([
            'client' => 'required|exists:clients,id',
            'invoice_no' => 'required|unique:work_orders,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required'
        ]);

        // Check WorkOrder Record
        $WorkOrder = WorkOrder::find($id);
        if (!$WorkOrder) {
            return back()->withErrors(['error' => 'Work Order not found!']);
        }

        // Extract Product Data
        $productIds = explode(',', $request->input('product_ids'));  
        $quantities = explode(',', $request->input('quantities'));  
        $prices = explode(',', $request->input('prices'));  

        // // Debug Product Data
        // Log::info('Product Data:', [
        //     'product_ids' => $productIds,
        //     'quantities' => $quantities,
        //     'prices' => $prices
        // ]);

        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            return back()->with('error', 'At least one product must be selected.');
        }

        try {
            DB::beginTransaction();

            // Update WorkOrder
            $WorkOrder->client_id = $validated['client'];  // Ensure this field exists in DB
            $WorkOrder->invoice_no = $validated['invoice_no'];
            $WorkOrder->invoice_date = $validated['invoice_date'];
            $WorkOrder->subtotal = $validated['subtotal'];
            $WorkOrder->discount = $validated['discount'];
            $WorkOrder->total = $validated['total'];
            $WorkOrder->save();

    
            // Delete Old Products
            WorkOrderProduct::where('work_order_id', $id)->delete();

            // Insert New Products
            foreach ($productIds as $index => $productId) {
                WorkOrderProduct::create([
                    'work_order_id' => $WorkOrder->id,
                    'product_id' => $productId,
                    'quantity' => $quantities[$index],
                    'price' => $prices[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('workorders.index')->with('success', 'Work Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Update Sale Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();
    
        return redirect()->back()->with('success', 'Work Order deleted successfully!');

        // Find the WorkOrder by its ID
        $WorkOrder = WorkOrder::find($id);

        // Check if the WorkOrder exists
        if ($WorkOrder) {
            // Manually delete the related WorkOrder records (pivot table entries)
            \DB::table('work_order_product')->where('work_order_id', $WorkOrder->id)->delete();

            // Delete the quotation
            $WorkOrder->delete();

            // Return a response indicating success
            return redirect()->back()->with('success', 'Work Order and related products deleted successfully.');
        }
    }

    public function invoice(string $id)
    {
        $workorder = WorkOrder::findOrFail($id);
        $pageTitle = 'Work Order Invoice';

        return view('backend.admin.inventory.workorder.invoice', compact('workorder', 'pageTitle'));
    }

    
}
