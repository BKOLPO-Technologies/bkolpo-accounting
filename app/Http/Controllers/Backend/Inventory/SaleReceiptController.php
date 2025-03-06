<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Receipt;
use App\Models\Supplier;
use App\Models\LedgerGroup;
use App\Models\IncomingChalan;
use App\Models\OutcomingChalan;
use App\Models\LedgerGroupDetail;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
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
            //->whereNotNull('outcoming_chalan_id')
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
        // $suppliers = Supplier::latest()->get();
        $customers = Client::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();

        return view('backend.admin.inventory.sales.receipt.create',compact('pageTitle','outcomingChalans','customers','ledgerGroups')); 
    }

    // public function getChalansByClient(Request $request)
    // {
    //     //dd($request->client_id);
    //     // Step 1: Find sales where client_id matches
    //     $sales = Sale::where('client_id', $request->client_id)->pluck('id'); 
    //     //dd($sales);
    //     // Step 2: Find Outcoming Chalans based on sale_id
    //     $chalans = OutcomingChalan::whereIn('sale_id', $sales)
    //         ->whereHas('sale', function($query) {
    //             $query->where('status', '!=', 'paid'); 
    //         })
    //         ->with('sale') // Ensure related purchase invoice is fetched
    //         ->get();
    //     //dd($chalans);
    //     // Step 3: Format the response
    //     $formattedChalans = $chalans->map(function ($chalan) {
    //         return [
    //             'id' => $chalan->id,
    //             'invoice_no' => $chalan->sale->invoice_no ?? 'N/A',
    //             'total_amount' => $chalan->sale->total-$chalan->sale->paid_amount ?? 0
    //         ];
    //     });

    //     return response()->json(['chalans' => $formattedChalans]);
    // }

    public function getChalansByClient(Request $request)
    {
        //Log::info('Hit');

        // Step 1: Find sales where client_id matches and status is not 'paid'
        $sales = Sale::where('client_id', $request->client_id)
            ->where('status', '!=', 'paid')
            ->get(['id', 'invoice_no', 'total', 'paid_amount']);

        //Log::info('Sales = ' . json_encode($sales));

        // Step 2: Format the response
        $formattedSales = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'invoice_no' => $sale->invoice_no ?? 'N/A',
                'total_amount' => ($sale->total ?? 0) - ($sale->paid_amount ?? 0),
            ];
        });

        return response()->json(['sales' => $formattedSales]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // this is not ok
        // payment/receipt/create
        //dd($request->all());
        // Validate the incoming form data
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            //'outcoming_chalan_id' => 'nullable|exists:outcoming_chalans,id',
            'invoice_no' => 'required',
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
                              ->where('client_id', $request->input('client_id'))
                              ->first();

            // dd("Receipt = ", $receipt);
    

            // Create a new receipt
            $receipt = Receipt::create([
                'client_id' => $request->input('client_id'),
                'ledger_id' => '1',
                //'outcoming_chalan_id' => $request->input('outcoming_chalan_id'),
                'invoice_no' => $request->input('invoice_no'),
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_date' => $request->input('payment_date'),
                'status' => 'outcoming',
            ]);

            //dd("Receipt = ", $receipt);
    
            // Find the sale based on the sale ID and outcoming chalan (you can adjust this logic based on your relationships)
            // $sale = Sale::where('client_id', $request->input('client_id'))->first();
            $sale = Sale::where('client_id', $request->input('client_id'))
                ->where('invoice_no', $request->input('invoice_no'))
                ->first();
            //dd("sale = ", $sale);

            // journal payment receipt add amount
            $sale_amount = $sale->total ?? 0; // Get the total sale amount

            //dd("sale_amount = ", $sale_amount);

            // Step 3: Get payment method from request (Cash, Bank, etc.)
            $payment_method = $request->input('payment_method'); // Get payment method from request
            $ledger = null;

            // dd("ledger = ", $ledger);

            // Step 4: Based on payment method, get the corresponding ledger
            if ($payment_method == 'cash') {
                $ledger = Ledger::where('name', 'Cash')->first(); // Find Cash ledger
            } elseif ($payment_method == 'bank') {
                $ledger = Ledger::where('name', 'Bank')->first(); // Find Bank ledger
            }

            //dd("ledger = ", $ledger);

            // Step 1: Get the Sales ledger
            $salesLedger = Ledger::where('name', 'Sales')->first();

            //dd("salesLedger = ", $salesLedger);

            // Step 2: Ensure the ledger exists for the given payment method (Cash or Bank)
            $paymentMethod = $request->input('payment_method'); // Get payment method (Cash/Bank)
            //$paymentLedger = Ledger::where('name', $paymentMethod)->first(); // Find the Cash or Bank ledger
            $paymentLedger = $ledger; // Find the Cash or Bank ledger

            //dd("paymentMethod = ", $paymentMethod);
            //dd("paymentLedger = ", $paymentLedger);

            if ($salesLedger && $paymentLedger) {

                //dd("hello");

                // Step 3: Determine the payment amount (can come from the request)
                $paymentAmount = $request->input('pay_amount', 0); // Amount being paid (in your case, 73)

                //dd("paymentAmount = ", $paymentAmount);
            
                // Step 4: Check if this invoice already exists in JournalVoucher
                $journalVoucher = JournalVoucher::where('transaction_code', $sale->invoice_no)->first();

                //dd("journalVoucher = ", $journalVoucher);
                
                if ($journalVoucher) {
                    // Step 5: Update existing journal voucher
                    $journalVoucher->update([
                        'transaction_date' => $request->input('payment_date'),
                        'description' => $request->input('description'),
                    ]);
                
                    // Step 6: Find the existing Sales ledger entry (debit) and subtract the payment amount
                    $salesLedgerDetail = JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                        ->where('ledger_id', $salesLedger->id)
                        ->first();
                
                    if ($salesLedgerDetail) {
                        // Existing Sales ledger debit amount (let's assume it's 2173)
                        $existingDebit = $salesLedgerDetail->debit;
                
                        // New debit amount after payment (decrease by payment amount)
                        $newDebitAmount = $existingDebit - $paymentAmount;
                
                        // Ensure debit does not go negative
                        $newDebitAmount = max(0, $newDebitAmount);
                
                        // Update the Sales ledger debit amount by reducing the payment amount
                        $salesLedgerDetail->update([
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
                        'debit'              => $paymentAmount, 
                        'credit'             => 0, 
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                } 
                
            }

            //dd('fail');
            


            // If sale exists
            if ($sale) {
                // Update the paid amount
                $sale->paid_amount += $request->input('pay_amount');

                // dd($sale->total,$sale->paid_amount);

                // Check if the total paid amount is equal to or greater than the sale amount
                if ($sale->paid_amount >= $sale->total) {

                    // If fully paid, update status to 'paid'
                    $sale->status = 'paid';
                } else {
                    // dd('not paid');
                    // If partially paid, update status to 'partially_paid'
                    $sale->status = 'partially_paid';
                }

                // Save the updated sale
                $sale->save();
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
