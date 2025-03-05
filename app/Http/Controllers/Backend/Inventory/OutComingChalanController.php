<?php

namespace App\Http\Controllers\Backend\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\Product;

use App\Models\StockIn;
use App\Models\Sale;
use App\Models\Ledger;
use App\Models\StockOut;
use App\Models\Supplier;

use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use App\Models\OutcomingChalan;
use App\Models\InChalanInventory;
use App\Models\OutChalanInventory;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log; 
use App\Models\OutcomingChalanProduct;

class OutComingChalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Out Going Chalan List';
    
        // Fetch all outcoming chalans with related sale details
        $outcomingchalans = OutcomingChalan::with('sale')->latest()->get();
    
        return view('backend.admin.inventory.sales.chalan.index', compact('pageTitle', 'outcomingchalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $pageTitle = 'Out Going Chalan';

        // $sales = Sale::latest()->get();

        // Fetch all sales with their associated OutcomingChalans and OutcomingChalanProducts
        $sales = Sale::with(['outcomingChalans.outcomingChalanProducts', 'saleProducts'])
            ->get()
            ->filter(function ($sale) {
                // Sum the quantities and received quantities
                $totalQuantity = 0;
                $totalReceivedQuantity = 0;

                // Fetch quantity from SaleProduct for each sale
                foreach ($sale->saleProducts as $saleProduct) {
                    // This assumes `quantity` is in the SaleProduct model
                    $totalQuantity += $saleProduct->quantity;  // Assuming SaleProduct has quantity field
                }

                // Loop through each OutcomingChalan associated with the sale
                foreach ($sale->outcomingChalans as $chalan) {
                    // Sum the quantity and received quantity for each OutcomingChalanProduct
                    foreach ($chalan->outcomingChalanProducts as $product) {
                        //$totalQuantity = $product->quantity;
                        $totalReceivedQuantity += $product->receive_quantity;

                       //Log::info('Product Quantity:', ['quantity' => $product->quantity, 'receive_quantity' => $product->receive_quantity]);
                    }
                }
                // Log::info('Total Quantity for Sale ID ' . $sale->id . ':', ['totalQuantity' => $totalQuantity]);
                // Log::info('Total Received Quantity for Sale ID ' . $sale->id . ':', ['totalReceivedQuantity' => $totalReceivedQuantity]);

                // Check if the total quantity is greater than the total received quantity
                return $totalQuantity > $totalReceivedQuantity;
            });

        return view('backend.admin.inventory.sales.chalan.create',compact('pageTitle','sales')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
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

        // journal add amount
        // Step 1: Fetch sale record
        $sale = Sale::find($request->sale_id);

        // Step 2: Get sale amount
        $sale_amount = $sale->total ?? 0; // If sale doesn't have amount, default to 0

        // Step 3: Retrieve Sales ledger
        $ledger = Ledger::where('name', 'Sales')->first();
        
        // Step 4: If the ledger exists, proceed with journal creation/update
        if ($ledger) {
            // Step 5: Check if this invoice already exists in JournalVoucher
            $journalVoucher = JournalVoucher::where('transaction_code', $sale->invoice_no)->first();

            // If journal voucher exists, update it; otherwise, create a new one
            if ($journalVoucher) {
                // Update existing journal voucher
                $journalVoucher->update([
                    'transaction_date' => $request->invoice_date,
                    'description' => $request->description,
                ]);

                // Update the corresponding journal voucher details
                JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                    ->where('ledger_id', $ledger->id)
                    ->update([
                        'debit' => $sale_amount, // Update the debit amount
                        'credit' => 0,           // Credit is zero for debit entry
                        'updated_at' => now(),
                    ]);
            } else {
                // If journal voucher does not exist, create a new one
                $journalVoucher = JournalVoucher::create([
                    'transaction_code'  => $sale->invoice_no,
                    'transaction_date'  => $request->invoice_date,
                    'description'       => $request->description,
                    'status'            => 1, // Pending status
                ]);

                // Create journal voucher details
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $ledger->id,
                    'reference_no'       => $request->reference_no ?? '',
                    'description'        => $request->description ?? '',
                    'debit'              => $sale_amount,
                    'credit'             => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        }
        

        return redirect()->route('outcoming.chalan.index')->with('success', 'Out Coming Chalan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function view(string $id)
    {
        $pageTitle = 'Out Going Chalan';

        $outcomingChalan = OutcomingChalan::with('sale', 'products')->findOrFail($id);

        $sales = Sale::latest()->get();

        return view('backend.admin.inventory.sales.chalan.view',compact('pageTitle','sales', 'outcomingChalan')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Out Going Chalan';

        $outcomingChalan = OutcomingChalan::with('sale', 'products')->findOrFail($id);

        $sales = Sale::latest()->get();

        return view('backend.admin.inventory.sales.chalan.edit',compact('pageTitle','sales', 'outcomingChalan')); 
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
