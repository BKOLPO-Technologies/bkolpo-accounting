<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Product;

use App\Models\StockIn;
use App\Models\Sale;
use App\Models\StockOut;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\OutcomingChalan;
use App\Models\InChalanInventory;
use App\Models\OutChalanInventory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 
use App\Models\OutcomingChalanProduct;

class OutComingChalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Out Coming Chalan List';
    
        // Fetch all outcoming chalans with related sale details
        $outcomingchalans = OutcomingChalan::with('sale')->latest()->get();
    
        return view('backend.admin.inventory.purchase.chalan.index', compact('pageTitle', 'outcomingchalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $pageTitle = 'Out Coming Chalan';

        $sales = Sales::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.create',compact('pageTitle','sales')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'invoice_date' => 'required|date',
            'description' => 'nullable|string',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'receive_quantity' => 'required|array',
        ]);

        // Create OutcomingChalan record
        $outcomingChalan = OutcomingChalan::create([
            'sale_id' => $request->sale_id,
            'invoice_date' => $request->invoice_date,
            'description' => $request->description,
        ]);

        // Insert product details into Out Coming Chalan Product table
        foreach ($request->product_id as $index => $productId) {
            $outcomingChalanProduct = OutcomingChalanProduct::create([
                'outcoming_chalan_id' => $outcomingChalan->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
                'receive_quantity' => $request->receive_quantity[$index],
            ]);

            // Fetch matching InChalanInventory record to get the reference_lot
            //$inChalanInventory = StockIn::where('product_id', $productId)->latest()->first();
            
            // if (!$inChalanInventory) {
            //     throw new \Exception("No matching InChalanInventory found for Product ID: {$productId}");
            // }

            // Fetch product details
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product with ID {$productId} not found.");
            }

            // Insert into OutChalanInventory
            // StockOut::create([
            //     'reference_lot' => 'Ref-' . $outcomingChalan->id . '-' . $productId, // Matching based on product
            //     'product_id' => $productId,
            //     'purchase_id' => $request->purchase_id,
            //     'outcoming_chalan_product_id' => $outcomingChalanProduct->id, // Correctly referencing the created record
            //     'quantity' => $request->receive_quantity[$index],
            //     'price' => $product->price * $request->receive_quantity[$index], // Calculate price based on quantity received
            // ]);
            
        }

        return redirect()->route('outcoming.chalan.index')->with('success', 'Out Coming Chalan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function view(string $id)
    {
        $pageTitle = 'Out Coming Chalan';

        $chalan = OutcomingChalan::with('sale', 'products')->findOrFail($id);

        $sales = Sale::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.view',compact('pageTitle','sales', 'chalan')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Out Coming Chalan';

        $chalan = OutcomingChalan::with('sale', 'products')->findOrFail($id);

        $sales = Sale::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.edit',compact('pageTitle','sales', 'chalan')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request->all());

        $request->validate([
            'description' => 'nullable|string|max:1000',
            'receive_quantity' => 'required|array',
            'receive_quantity.*' => 'integer|min:0', // Validate each quantity as an integer
        ]);

        // Find the IncomingChalan record
        $outcomingChalan = OutcomingChalan::findOrFail($id);

        // Update only the description
        $outcomingChalan->update([
            'description' => $request->description,
        ]);

        // Update receive_quantity for each product
        foreach ($request->receive_quantity as $index => $qty) {
            $outcomingChalan->products[$index]->update([
                'receive_quantity' => $qty,
            ]);
        }

        return redirect()->route('outcoming.chalan.index')->with('success', 'Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chalan = OutcomingChalan::findOrFail($id);

        // Optional: Delete related OutcomingChalanProduct records if they exist
        $chalan->products()->delete();

        // Delete the chalan record
        $chalan->delete();

        return redirect()->back()->with('success', 'Out Coming Chalan deleted successfully!');
    }

}
