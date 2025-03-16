<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Project;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use App\Models\ProjectReceipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;

class ProductSaleReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Project Sale Receive List';
    
        $receipts = ProjectReceipt::with(['client'])
            ->orderBy('id', 'desc')
            ->get();
        
        return view('backend.admin.inventory.project.payment.receipt.index', compact('pageTitle', 'receipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Project Receive Payment';

        $customers = Client::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();
        // $projects = Project::where('project_type','Running')->latest()->get();
        $projects = Project::where('project_type', 'Running')
            ->whereColumn('paid_amount', '<', 'grand_total')
            ->latest()
            ->get();


        return view('backend.admin.inventory.project.payment.receipt.create',compact('pageTitle', 'customers', 'ledgerGroups', 'projects'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming form data
        $request->validate([
            // 'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank',
            'payment_date' => 'required|date',
        ]);

        //dd($request->all());
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {

            $project = Project::where('id', $request->input('project_id'))->first();
  
            // Create a new project receipt
            $receipt = ProjectReceipt::create([
                'client_id' => $project->client_id,
                'invoice_no' => $project->reference_no ,
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_date' => $request->input('payment_date'),
                'status' => 'incoming',
            ]);

          

            // journal payment project receipt add amount
            $project_amount = $project->grand_total ?? 0; // Get the total project amount


            $paymentMethod = $request->input('payment_method');

            // Step 4: Based on payment method, get the corresponding ledger
            if ($paymentMethod == 'cash') {
                $ledger = Ledger::where('name', 'Cash')->first();
            } elseif ($paymentMethod == 'bank') {
                $ledger = Ledger::where('name', 'Bank')->first(); 
            }

            $cashBankLedger  = $ledger;
            $receivableLedger = Ledger::where('name', 'Accounts Receivable')->first();
        
            $paymentAmount = $request->input('pay_amount', 0); 

            if ($cashBankLedger && $receivableLedger) {
                // Check if a Journal Voucher exists for this payment transaction
                $journalVoucher = JournalVoucher::where('transaction_code', $project->invoice_no)->first();
            
                if (!$journalVoucher) {
                    // Create a new Journal Voucher for Payment Received
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code'  => $project->reference_no,
                        'transaction_date'  => $request->payment_date,
                        'description'       => 'Invoice Payment Received - First Installment', // ম্যানুয়াল বর্ণনা
                        'status'            => 1, // Pending status
                    ]);
                }
            
                // Payment Received -> Cash & Bank (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $cashBankLedger->id, // নগদ ও ব্যাংক হিসাব
                    'reference_no'       => $project->reference_no,
                    'description'        => 'Payment of ' . number_format($paymentAmount, 2) . ' Taka Received from Customer for Invoice ' . $project->invoice_no,
                    'debit'              => $paymentAmount, // টাকা জমা হচ্ছে
                    'credit'             => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            
                // Payment Received -> Accounts Receivable (Credit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $receivableLedger->id, 
                    'reference_no'       => $project->reference_no,
                    'description'        => 'Accounts Receivable Reduced by '.$paymentAmount.' Taka',
                    'debit'              => 0,
                    'credit'             => $paymentAmount,  // পাওনা টাকা কমবে
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // If project exists
            if ($project) {
                // Update the paid amount
                $project->paid_amount += $request->input('pay_amount');

                // dd($project->total,$project->paid_amount);

                // Check if the total paid amount is equal to or greater than the project amount
                if ($project->paid_amount >= $project->grand_total) {

                    // If fully paid, update status to 'paid'
                    $project->status = 'paid';
                } else {
                    // dd('not paid');
                    // If partially paid, update status to 'partially_paid'
                    $project->status = 'partially_paid';
                }

                // Save the updated project
                $project->save();
            }

            // Commit the transaction
            DB::commit();
    
            // Redirect after storing the payment
            return redirect()->route('project.receipt.payment.index')->with('success', 'Payment has been successfully recorded!');
    
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
            
            // Log the error with details
            Log::error('Payment storing failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all(),
            ]);
    
            // Log the error or return a custom error message
            return redirect()->back()->with('error', 'Payment failed! ' . $e->getMessage());
        }
    }
}
