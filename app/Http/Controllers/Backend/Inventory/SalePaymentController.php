<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\LedgerGroup;
use App\Models\IncomingChalan;
use App\Models\OutcomingChalan;
use App\Models\LedgerGroupDetail;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class SalePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Payment List';
    
        $payments = Payment::with(['ledger', 'client', 'supplier', 'incomingChalan', 'outcomingChalan'])
        ->orderBy('id', 'desc')
        ->whereNotNull('incoming_chalan_id')
        ->get();

        //dd($payments->toArray());
    
        return view('backend.admin.inventory.sales.payment.index', compact('pageTitle', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Payment';

        $incomingChalans = IncomingChalan::latest()->get();
        // $clients = Client::latest()->get();
        $suppliers = Supplier::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();

        return view('backend.admin.inventory.sales.payment.create',compact('pageTitle','incomingChalans','suppliers','ledgerGroups')); 
    }

    public function getLedgersByGroup(Request $request)
    {
        $ledgers = LedgerGroupDetail::where('group_id', $request->ledger_group_id)
                    ->with('ledger') // Fetch related Ledger details
                    ->get();
    
        // Transform data to return only necessary fields
        $formattedLedgers = $ledgers->map(function ($item) {
            return [
                'id' => $item->ledger->id,
                'name' => $item->ledger->name
            ];
        });
    
        return response()->json(['ledgers' => $formattedLedgers]);
    }

    public function getChalansBySupplier(Request $request)
    {
        //dd($request->supplier_id);
        // Step 1: Find Purchase where supplier_id matches
        $purchase = Purchase::where('supplier_id', $request->supplier_id)->pluck('id'); 
        //dd($purchase);

        // Step 2: Find Incoming Chalans based on purchase_id
        $chalans = IncomingChalan::whereIn('purchase_id', $purchase)
            ->whereHas('purchase', function($query) {
                $query->where('status', '!=', 'paid'); 
            })
            ->with('purchase') // Ensure related purchase invoice is fetched
            ->get();

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
            'incoming_chalan_id' => 'nullable|exists:incoming_chalans,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank',
            'payment_date' => 'required|date',
        ]);
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Check if the payment for this incoming chalan already exists
            $payment = Payment::where('incoming_chalan_id', $request->input('incoming_chalan_id'))
                              ->where('supplier_id', $request->input('supplier_id'))
                              ->first();
    
            // Create a new payment
            $payment = Payment::create([
                'supplier_id' => $request->input('supplier_id'),
                'ledger_id' => '1',
                'incoming_chalan_id' => $request->input('incoming_chalan_id'),
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_date' => $request->input('payment_date'),
            ]);
        
            // Find the supplier_id based on the supplier_id ID and incoming chalan (you can adjust this logic based on your relationships)
            $purchases = Purchase::where('supplier_id', $request->input('supplier_id'))->first();
            
            $purchase_amount = $purchases->total ?? 0;

            // Step 3: Get payment method from request (Cash, Bank, etc.)
            $payment_method = $request->input('payment_method'); // Get payment method from request
            $ledger = null;

            // Step 4: Based on payment method, get the corresponding ledger
            if ($payment_method == 'cash') {
                $ledger = Ledger::where('name', 'Cash')->first(); // Find Cash ledger
            } elseif ($payment_method == 'bank') {
                $ledger = Ledger::where('name', 'Bank')->first(); // Find Bank ledger
            }

            // Step 1: Get the Purchase ledger
            $purchasesLedger =  Ledger::where('name', 'Purchase Accounts')->first();

            // Step 2: Ensure the ledger exists for the given payment method (Cash or Bank)
            $paymentMethod = $request->input('payment_method'); // Get payment method (Cash/Bank)
            $paymentLedger = Ledger::where('name', $paymentMethod)->first(); // Find the Cash or Bank ledger

            if ($purchasesLedger && $paymentLedger) {
                // Step 3: Determine the payment amount (can come from the request)
                $paymentAmount = $request->input('pay_amount', 0); // Amount being paid (in your case, 73)
            
                // Step 4: Check if this invoice already exists in JournalVoucher
                $journalVoucher = JournalVoucher::where('transaction_code', $purchases->invoice_no)->first();
                
                if ($journalVoucher) {
                    // Step 5: Update existing journal voucher
                    $journalVoucher->update([
                        'transaction_date' => $request->input('payment_date'),
                        'description' => $request->input('description'),
                    ]);
                
                    // Step 6: Find the existing Purchases ledger entry (debit) and subtract the payment amount
                    $purchasesLedgerDetail = JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                        ->where('ledger_id', $purchasesLedger->id)
                        ->first();
                
                    if ($purchasesLedgerDetail) {
                        // Existing Sales ledger debit amount (let's assume it's 2173)
                        $existingDebit = $purchasesLedgerDetail->debit;
                
                        // New debit amount after payment (decrease by payment amount)
                        $newDebitAmount = $existingDebit - $paymentAmount;
                
                        // Ensure debit does not go negative
                        $newDebitAmount = max(0, $newDebitAmount);
                
                        // Update the Sales ledger debit amount by reducing the payment amount
                        $purchasesLedgerDetail->update([
                            'debit' => $newDebitAmount,  // Update Sales ledger debit to the new value (after payment)
                            'credit' => 0,  // No credit for Sales ledger
                            'updated_at' => now(),
                        ]);
                    }

                    // Step 9: Create Journal Voucher Detail for Payment (Cash or Bank ledger) - credit the payment amount
                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id'          => $paymentLedger->id,  // Cash or Bank ledger
                        'reference_no'       => $request->input('reference_no', ''),
                        'description'        => $request->input('description', ''),
                        'debit'              => 0, 
                        'credit'             => $paymentAmount, 
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                } 
                
            }

            // If purchases exists
            if ($purchases) {
                // Update the paid amount
                $purchases->paid_amount += $request->input('pay_amount');

                // dd($purchases->total,$purchases->paid_amount);

                // Check if the total paid amount is equal to or greater than the purchases amount
                if ($purchases->paid_amount >= $purchases->total) {


                    // If fully paid, update status to 'paid'
                    $purchases->status = 'paid';
                } else {
                    // dd('not paid');
                    // If partially paid, update status to 'partially_paid'
                    $purchases->status = 'partially_paid';
                }

                // Save the updated purchases
                $purchases->save();
            }
            

    
            // Commit the transaction
            DB::commit();
    
            // Redirect after storing the payment
            return redirect()->route('sale.payment.index')->with('success', 'Payment has been successfully recorded!');
    
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
