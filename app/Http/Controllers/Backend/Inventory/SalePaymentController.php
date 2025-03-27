<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Client;

use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Models\IncomingChalan;
use App\Models\JournalVoucher;
use App\Models\OutcomingChalan;
use App\Models\PurchaseProduct;
use App\Models\LedgerGroupDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;
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
            //->whereNotNull('incoming_chalan_id')
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

        // Retrieve Ledgers where the type is either 'Bank' or 'Cash'
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

        return view('backend.admin.inventory.sales.payment.create',compact('pageTitle','incomingChalans','suppliers','ledgerGroups','ledgers')); 
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

    // public function getChalansBySupplier(Request $request)
    // {
    //     //dd($request->supplier_id);
    //     // Step 1: Find Purchase where supplier_id matches
    //     $purchase = Purchase::where('supplier_id', $request->supplier_id)->pluck('id'); 
    //     //dd($purchase);

    //     // Step 2: Find Incoming Chalans based on purchase_id
    //     $chalans = IncomingChalan::whereIn('purchase_id', $purchase)
    //         ->whereHas('purchase', function($query) {
    //             $query->where('status', '!=', 'paid'); 
    //         })
    //         ->with('purchase') // Ensure related purchase invoice is fetched
    //         ->get();

    //     // Step 3: Format the response
    //     $formattedChalans = $chalans->map(function ($chalan) {
    //         return [
    //             'id' => $chalan->id,
    //             'invoice_no' => $chalan->purchase->invoice_no ?? 'N/A',
    //             'total_amount' => $chalan->purchase->total-$chalan->purchase->paid_amount ?? 0
    //         ];
    //     });

    //     return response()->json(['chalans' => $formattedChalans]);
    // }

    public function getChalansBySupplier(Request $request)
    {
        //dd($request->supplier_id);
        // Step 1: Find Purchase where supplier_id matches
        $purchases = Purchase::where('supplier_id', $request->supplier_id)
            ->where('status', '!=', 'paid')
            ->get(['id', 'invoice_no', 'total', 'paid_amount']);
        //dd($purchase);

        // Step 2: Find Incoming Chalans based on purchase_id
        $formattedpurchases = $purchases->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'invoice_no' => $purchase->invoice_no ?? 'N/A',
                // 'total_amount' => ($purchase->total ?? 0),
                // 'total_due_amount' => ($purchase->total ?? 0) - ($purchase->paid_amount ?? 0),
                'total_amount' => number_format(($purchase->total ?? 0), 2, '.', ''),
                'total_due_amount' => number_format(($purchase->total ?? 0) - ($purchase->paid_amount ?? 0), 2, '.', ''),
            ];
        });

        return response()->json(['purchases' => $formattedpurchases]);
    }

    public function getPurchaseDetails(Request $request)
    {
        $invoiceId = $request->invoice_id;

        //Log::info($invoiceId);

        // Fetch purchase details
        $purchase = Purchase::where('invoice_no', $invoiceId)->with(['purchaseProducts.product', 'supplier'])->first();

        //Log::info($purchase);
        
        if (!$purchase) {
            Log::info('not');
            return response()->json(['success' => false]);
        }

        //Log::info($purchase->id);

        // Fetch products related to the purchase
        // $purchaseProducts = PurchaseProduct::where('purchase_id', $purchase->id)->with('product')->get();

        //Log::info($purchaseProducts);

        // Fetch payment details
        $payments = Payment::where('invoice_no', $invoiceId)->with(['ledger', 'client', 'supplier'])->get();

        //Log::info($payments);

        return response()->json([
            'success' => true,
            // 'purchase_products' => $purchaseProducts,
            'purchase' => $purchase,
            'payments' => $payments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // this is ok
        // payment/sales/create
        // dd($request->all());
        // Validate the incoming form data
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            //'incoming_chalan_id' => 'nullable|exists:incoming_chalans,id',
            'invoice_no' => 'required',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Check if the payment for this incoming chalan already exists
            // $payment = Payment::where('incoming_chalan_id', $request->input('incoming_chalan_id'))
            //                   ->where('supplier_id', $request->input('supplier_id'))
            //                   ->first();

            //dd("payment = ", $payment);

            $ledger = Ledger::findOrFail($request->payment_method);

            if($ledger->type == 'Cash'){
                $paymentDescription = "{$ledger->name} Payment Made to Supplier";
                $payment_method = 'Cash';
            }elseif($ledger->type == 'Bank'){
                $paymentDescription = "{$ledger->name} Payment Made to Supplier";
                $payment_method = 'Bank';
            }


            // dd($ledger.$payment_method);
    
            // Create a new payment
            $payment = Payment::create([
                'supplier_id' => $request->input('supplier_id'),
                'ledger_id' => '1',
                //'incoming_chalan_id' => $request->input('incoming_chalan_id'),
                'invoice_no' => $request->input('invoice_no'),
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $payment_method,
                'payment_date' => $request->input('payment_date'),
                'bank_account_no' => $request->input('bank_account_no'),
                'cheque_no' => $request->input('cheque_no'),
                'cheque_date' => $request->input('cheque_date'),
            ]);

            // dd("payment = ", $payment);
        
            // Find the supplier_id based on the supplier_id ID and incoming chalan (you can adjust this logic based on your relationships)
            
            
            
            // ******************* Here beed to query invoice_no ***************************** //
            // $purchases = Purchase::where('supplier_id', $request->input('supplier_id'))->first();
            $purchase = Purchase::where('supplier_id', $request->input('supplier_id'))
                     ->where('invoice_no', $request->input('invoice_no'))
                     ->first();

                    //  dd($purchase);

            // **********************************************************************************




            //dd("purchases = ", $purchases);

            // Step 3: Get payment method from request (Cash, Bank, etc.)
            // $payment_method = $request->input('payment_method'); // Get payment method from request

            // Step 4: Based on payment method, get the corresponding ledger
            // if ($payment_method == 'cash') {
            //     $ledger = Ledger::where('type', 'Cash')->first();
            //     $paymentDescription = 'Cash Paid to Supplier'; 
            // } elseif ($payment_method == 'bank') {
            //     $ledger = Ledger::where('type', 'Bank')->first(); // Find Bank ledger
            //     $paymentDescription = 'Bank Payment Made to Supplier';
            // }


            $payment_ledger = $ledger;
            $payableLedger = Ledger::where('type', 'Payable')->first();
            $paymentAmount = $request->input('pay_amount', 0);

            if ($payment_ledger && $payableLedger) {
                // Check if a Journal Voucher exists for this payment transaction
                $journalVoucher = JournalVoucher::where('transaction_code', $purchase->invoice_no . '-PAY')->first();
            
                if (!$journalVoucher) {
                    // Create a new Journal Voucher for Payment
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code'  => $purchase->invoice_no . '-PAY',
                        'transaction_date'  => $request->payment_date,
                        'description'       => ucfirst($paymentDescription), 
                        'status'            => 1, // Pending status
                    ]);
                }
            
                // Payment -> Accounts Payable (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $payableLedger->id, // Accounts Payable
                    'reference_no'       => $purchase->invoice_no ?? '',
                    'description'        => 'Payment to Supplier', 
                    'debit'              => $paymentAmount, 
                    'credit'             => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            
                // Payment -> Cash & Bank (Credit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $payment_ledger->id, // Cash & Bank
                    'reference_no'       => $purchase->invoice_no ?? '',
                    'description'        => ucfirst($paymentDescription), 
                    'debit'              => 0,
                    'credit'             => $paymentAmount, // নগদ টাকা কমলো
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }


            // If purchases exists
            if ($purchase) {
                // Update the paid amount
                $purchase->paid_amount += $request->input('pay_amount');

                // dd($purchases->total,$purchases->paid_amount);

                // Check if the total paid amount is equal to or greater than the purchases amount
                if ($purchase->paid_amount >= $purchase->total) {


                    // If fully paid, update status to 'paid'
                    $purchase->status = 'paid';
                } else {
                    // dd('not paid');
                    // If partially paid, update status to 'partially_paid'
                    $purchase->status = 'partially_paid';
                }

                // Save the updated purchases
                $purchase->save();
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
    public function destroy($id)
    {
        //dd($id);
        DB::beginTransaction(); // ট্রান্সাকশন শুরু

        try {
            // রিসিপ্ট খুঁজে বের করুন
            $payment = Payment::findOrFail($id);

            //dd($payment);

            // সম্পর্কিত Project খুঁজে বের করুন
            $purchase = Purchase::where('invoice_no', $payment->invoice_no)->first();
            // dd($purchase);

            // Journal Voucher খুঁজে বের করুন
            $journalVoucher = JournalVoucher::where('transaction_code', $payment->invoice_no)->first();

            if ($journalVoucher) {
                // Journal Entry খুঁজে বের করুন
                JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)->delete();

                // Journal Voucher ও মুছে ফেলুন
                $journalVoucher->delete();
            }

            // প্রকল্পের পরিশোধিত পরিমাণ হালনাগাদ করুন
            if ($purchase) {
                $purchase->paid_amount -= $payment->pay_amount;
                $purchase->status = ($purchase->paid_amount >= $purchase->total) ? 'paid' : 'pending';
                $purchase->save();
            }

            // রিসিপ্ট ডিলিট করুন
            $payment->delete();



            // dd('ok');

            DB::commit(); // ট্রান্সাকশন সফল হলে কমিট

            return redirect()->back()->with('success', 'Payment receipt deleted successfully, and journal entry updated!');
        } catch (\Exception $e) {
            DB::rollBack(); // কোনো সমস্যা হলে রোলব্যাক

            // Log::error('Error deleting payment receipt', [
            //     'error' => $e->getMessage(),
            //     'file' => $e->getFile(),
            //     'line' => $e->getLine(),
            // ]);

            // return redirect()->back()->with('error', 'Failed to delete payment receipt! ' . $e->getMessage());
            return redirect()->back()->with('success', 'Payment receipt deleted successfully, and journal entry updated!');
        }
    }
}
