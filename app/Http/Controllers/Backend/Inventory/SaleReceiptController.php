<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Receipt;
use App\Models\Supplier;
use App\Models\LedgerGroup;
use App\Models\IncomingChalan;
use App\Models\OutcomingChalan;
use App\Models\LedgerGroupDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class SaleReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Receive Payment List';
    
        $receipts = Receipt::with(['ledger', 'client', 'supplier', 'incomingChalan', 'outcomingChalan'])
        ->orderBy('payment_date', 'desc')
        ->whereNotNull('outcoming_chalan_id')
        ->get();
    
        return view('backend.admin.inventory.sales.receipt.index', compact('pageTitle', 'receipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Receive Payment';

        $outcomingChalans = OutcomingChalan::latest()->get();
        $suppliers = Supplier::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();

        return view('backend.admin.inventory.sales.receipt.create',compact('pageTitle','outcomingChalans','suppliers','ledgerGroups')); 
    }

    public function getChalansBySupplier(Request $request)
    {
        //dd($request->supplier_id);
        // Step 1: Find sales where suplier_id matches
        $purchases = Purchase::where('supplier_id', $request->supplier_id)->pluck('id'); 
        //dd($purchases);
        // Step 2: Find Outcoming Chalans based on purchase_id
        $chalans = OutcomingChalan::whereIn('purchase_id', $purchases)
            ->with('purchase') // Ensure related purchase invoice is fetched
            ->get();
        //dd($chalans);
        // Step 3: Format the response
        $formattedChalans = $chalans->map(function ($chalan) {
            return [
                'id' => $chalan->id,
                'invoice_no' => $chalan->purchase->invoice_no ?? 'N/A',
                'total_amount' => $chalan->purchase->total-$chalan->purchase->paid_amount ?? 0
            ];
        });

        return response()->json(['chalans' => $formattedChalans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming form data
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'outcoming_chalan_id' => 'nullable|exists:outcoming_chalans,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank',
            'payment_date' => 'required|date',
        ]);
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Check if the Receipt for this outcoming chalan already exists
            $receipt = Receipt::where('outcoming_chalan_id', $request->input('outcoming_chalan_id'))
                              ->where('supplier_id', $request->input('supplier_id'))
                              ->first();
    

            // Create a new receipt
            $receipt = Receipt::create([
                'supplier_id' => $request->input('supplier_id'),
                'ledger_id' => '1',
                'outcoming_chalan_id' => $request->input('outcoming_chalan_id'),
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_date' => $request->input('payment_date'),
                'status' => 'outcoming',
            ]);
    
            // Find the Purchase based on the Purchase ID and outcoming chalan (you can adjust this logic based on your relationships)
            $purchase = Purchase::where('supplier_id', $request->input('supplier_id'))->first();
    
            // If purchase exists
            if ($purchase) {
                // Update the paid amount and remaining amount
                $purchase->paid_amount += $request->input('pay_amount');
              
                $purchase->save();
            }
    
            // Commit the transaction
            DB::commit();
    
            // Redirect after storing the payment
            return redirect()->route('receipt.payment.index')->with('success', 'Payment has been successfully recorded!');
    
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
    
            // Log the error or return a custom error message
            return redirect()->back()->with('error', 'Payment failed! ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
