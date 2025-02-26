<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Sales List';

        $sales = Sale::with('products')->OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.sales.index',compact('pageTitle','sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Sales';

        // Generate a random 8-digit number
        $randomNumber = mt_rand(100000, 999999);

        $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.sales.create',compact('pageTitle', 'clients', 'products','invoice_no')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'client' => 'required|exists:clients,id',
            'invoice_no' => 'required|unique:sales,invoice_no',
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

            // Create a new sale record
            $sale = new Sale();
            $sale->client_id = $validated['client'];
            $sale->invoice_no = $validated['invoice_no'];
            $sale->invoice_date = $validated['invoice_date'];
            $sale->subtotal = $validated['subtotal'];
            $sale->discount = $validated['discount'];
            $sale->total = $validated['total'];
            $sale->save();

            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $quantities[$index];
                $price = $prices[$index];

                // Insert into SaleProduct table
                $saleProduct = new SaleProduct();
                $saleProduct->sale_id = $sale->id; // Link to the sale
                $saleProduct->product_id = $productId; // Product ID
                $saleProduct->quantity = $quantity; // Quantity
                $saleProduct->price = $price; // Price
                $saleProduct->save(); // Save the record
            }

            // Commit the transaction
            \DB::commit();

            // Redirect back with a success message
            return redirect()->route('admin.sale.index')->with('success', 'Sale created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            \DB::rollback();

            // Log the error message
            Log::error('Sale creation failed: ', [
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
    public function view($id)
    {
        $pageTitle = 'Sales View';

        $sale = Sale::where('id', $id)
            ->with(['products', 'client']) // Include supplier details
            ->first();

        return view('backend.admin.inventory.sales.view',compact('pageTitle', 'sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pageTitle = 'Sale Edit';

        // $purchase = Purchase::where('id', $id)->with('products')->first();
        // Fetch purchase details with supplier and products
        $sale = Sale::where('id', $id)
            ->with(['products', 'client']) // Include supplier details
            ->first();

        // $sale = Sale::where('id', $id)
        //     ->with(['saleProducts', 'client']) // Include supplier details
        //     ->first();
        
        if ($sale->invoice_date) {
            $sale->invoice_date = Carbon::parse($sale->invoice_date);
        }

        $subtotal = $sale->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });

        $clients = Client::orderBy('id', 'desc')->get();
        $products = Product::where('status',1)->latest()->get();

        return view('backend.admin.inventory.sales.edit',compact('pageTitle', 'sale', 'clients', 'products', 'subtotal')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        // dd($request->all());
        // // Debug Request Data
        // Log::info('Request Data:', $request->all());

        // Validate Request
        $validated = $request->validate([
            'client' => 'required|exists:clients,id',
            'invoice_no' => 'required|unique:purchases,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required'
        ]);

        // Check Sale Record
        $sale = Sale::find($id);
        if (!$sale) {
            return back()->withErrors(['error' => 'Sale not found!']);
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

            // Update Sale
            $sale->client_id = $validated['client'];  // Ensure this field exists in DB
            $sale->invoice_no = $validated['invoice_no'];
            $sale->invoice_date = $validated['invoice_date'];
            $sale->subtotal = $validated['subtotal'];
            $sale->discount = $validated['discount'];
            $sale->total = $validated['total'];
            $sale->save();

            // Delete Old Products
            SaleProduct::where('sale_id', $id)->delete();

            // Insert New Products
            foreach ($productIds as $index => $productId) {
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantities[$index],
                    'price' => $prices[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('admin.sale.index')->with('success', 'Sale updated successfully!');
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
        //
    }
}
