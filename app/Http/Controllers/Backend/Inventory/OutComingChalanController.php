<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Product;

use App\Models\StockIn;
use App\Models\Purchase;
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
        $outcomingchalans = OutcomingChalan::with('purchase')->latest()->get();
    
        return view('backend.admin.inventory.purchase.chalan.index', compact('pageTitle', 'outcomingchalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $pageTitle = 'Out Coming Chalan';

        $purchases = Purchase::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.create',compact('pageTitle','purchases')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction(); // Start Transaction

        try {
            // Validate request data
            $request->validate([
                'purchase_id' => 'required|exists:purchases,id',
                'invoice_date' => 'required|date',
                'description' => 'nullable|string',
                'product_id' => 'required|array',
                'quantity' => 'required|array',
                'receive_quantity' => 'required|array',
            ]);

            // Create OutcomingChalan record
            $outcomingChalan = OutcomingChalan::create([
                'purchase_id' => $request->purchase_id,
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

                // Fetch product details
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Product with ID {$productId} not found.");
                }

                // Insert into OutChalanInventory (StockOut)
                StockOut::create([
                    'reference_lot' => 'Ref-' . $outcomingChalan->id . '-' . $productId,
                    'product_id' => $productId,
                    'purchase_id' => $request->purchase_id,
                    'outcoming_chalan_product_id' => $outcomingChalanProduct->id, // Correctly referencing the created record
                    'quantity' => $request->receive_quantity[$index],
                    'price' => $product->price * $request->receive_quantity[$index], // Calculate price based on quantity received
                ]);

                // Decrease product stock
                if ($product->quantity >= $request->receive_quantity[$index]) {
                    $product->decrement('quantity', $request->receive_quantity[$index]); // Decrease quantity in Product table
                } else {
                    throw new \Exception("Insufficient stock for Product ID {$productId}.");
                }
            }

            DB::commit(); // Commit the transaction

            // Success message after processing everything correctly
            return redirect()->route('outcoming.chalan.index')->with('success', 'Out Coming Chalan created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on failure
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function view(string $id)
    {
        $pageTitle = 'Out Coming Chalan';

        $chalan = OutcomingChalan::with('purchase', 'products')->findOrFail($id);

        $purchases = Purchase::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.view',compact('pageTitle','purchases', 'chalan')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Out Coming Chalan';

        $chalan = OutcomingChalan::with('purchase', 'products')->findOrFail($id);

        $purchases = Purchase::latest()->get();

        return view('backend.admin.inventory.purchase.chalan.edit',compact('pageTitle','purchases', 'chalan')); 
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
