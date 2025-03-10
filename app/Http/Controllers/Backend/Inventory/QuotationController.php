<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Quotation List';

        $quotations = Quotation::with('products')->OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.quotation.index',compact('pageTitle','quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Quotation';

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $invoice_no = 'BCL-QO-'.$fullDate.' - '.$randomNumber;

        // // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.quotation.create',compact('pageTitle', 'clients', 'products','invoice_no')); 
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
            'invoice_no' => 'required|unique:quotations,invoice_no',
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
        $discounts = explode(',', $request->input('discounts'));  // Array of discounts

        // Check if at least one product is selected
        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            // If no product is selected, return an error message
            return back()->with('error', 'At least one product must be selected.');
        }

      

        try {
            // Start the transaction
            \DB::beginTransaction();

            // Create a new quotation record
            $quotation = new Quotation();
            $quotation->client_id = $validated['client'];
            $quotation->invoice_no = $validated['invoice_no'];
            $quotation->invoice_date = $validated['invoice_date'];
            $quotation->subtotal = $validated['subtotal'];
            $quotation->discount = $validated['discount'];
            $quotation->total = $validated['total'];
            $quotation->description = $request->description;
            $quotation->form_date = Carbon::now();
            $quotation->to_date = $request->to_date;
            $quotation->save();

            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $quantities[$index];
                $price = $prices[$index];
                $discount = $discounts[$index];

                // Insert into QuotationProduct table
                $quotationProduct = new QuotationProduct();
                $quotationProduct->quotation_id = $quotation->id; // Link to the sale
                $quotationProduct->product_id = $productId; // Product ID
                $quotationProduct->quantity = $quantity; // Quantity
                $quotationProduct->price = $price; // Price
                $quotationProduct->discount = $discount; // Discount
                $quotationProduct->save(); // Save the record
            }

            // Commit the transaction
            \DB::commit();

            // Redirect back with a success message
            return redirect()->route('quotations.index')->with('success', 'Quotation created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            \DB::rollback();

            // Log the error message
            Log::error('Quotation creation failed: ', [
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
        $quotation = Quotation::where('id', $id)
            ->with(['products', 'client']) // Include client details
            ->first();
        $pageTitle = 'Quotation View';

        return view('backend.admin.inventory.quotation.view',compact('pageTitle', 'quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Quotation Edit';

        $quotation = Quotation::where('id', $id)
            ->with(['products', 'client']) // Include client details
            ->first();
        
        if ($quotation->invoice_date) {
            $quotation->invoice_date = Carbon::parse($quotation->invoice_date);
        }

        $subtotal = $quotation->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });

        $clients = Client::orderBy('id', 'desc')->get();
        $products = Product::where('status',1)->latest()->get();

        return view('backend.admin.inventory.quotation.edit',compact('pageTitle', 'quotation', 'clients', 'products', 'subtotal')); 
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
            'invoice_no' => 'required|unique:quotations,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required'
        ]);

        // Check Quotation Record
        $quotation = Quotation::find($id);
        if (!$quotation) {
            return back()->withErrors(['error' => 'Quotation not found!']);
        }

        // Extract Product Data
        $productIds = explode(',', $request->input('product_ids'));  
        $quantities = explode(',', $request->input('quantities'));  
        $prices = explode(',', $request->input('prices'));  
        $discounts = explode(',', $request->input('discounts'));  

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

            // Update quotation
            $quotation->client_id = $validated['client'];  // Ensure this field exists in DB
            $quotation->invoice_no = $validated['invoice_no'];
            $quotation->invoice_date = $validated['invoice_date'];
            $quotation->subtotal = $validated['subtotal'];
            $quotation->discount = $validated['discount'];
            $quotation->total = $validated['total'];
            $quotation->description = $request->description;
            $quotation->form_date = Carbon::now();
            $quotation->to_date = $request->to_date;
            $quotation->save();

            // Delete Old Products
            QuotationProduct::where('quotation_id', $id)->delete();

            // Insert New Products
            foreach ($productIds as $index => $productId) {
                QuotationProduct::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $productId,
                    'quantity' => $quantities[$index],
                    'price' => $prices[$index],
                    'discount' => $discounts[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully!');
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
        // Find the quotation by its ID
        $quotation = Quotation::find($id);

        if ($quotation) {
            // Detach the related QuotationProduct records (pivot table entries)
            $quotation->products()->detach();
    
            // Delete the sale itself
            $quotation->delete();
    
            return redirect()->back()->with('success', 'Quotation and related products deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Quotation and related products deleted successfully..');
    }

}
