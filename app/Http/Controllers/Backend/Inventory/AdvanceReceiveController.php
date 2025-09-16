<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Project;
use App\Models\LedgerGroup;
use App\Models\JournalVoucher;
use App\Models\ProjectReceipt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JournalVoucherDetail;
use Exception;
use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\ProjectItem;
use Illuminate\Support\Str;
use App\Traits\ProjectSalesTrait;
use App\Traits\TrialBalanceTrait;
use Illuminate\Database\QueryException;

class AdvanceReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Advance Payment Receive List';
    
        $receipts = ProjectReceipt::with(['client'])
            ->orderBy('id', 'desc')
            ->get();
        
        return view('backend.admin.inventory.project.advance.payment.receipt.index', compact('pageTitle', 'receipts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Advance Receive Payment';

        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();
    
        $projects = Project::where('project_type', 'Running')
            ->with(['sales' => function($q) {
                $q->where('status', 'Paid')
                ->select('project_id', DB::raw('SUM(grand_total) as total_grand'), DB::raw('SUM(paid_amount) as total_paid'))
                ->groupBy('project_id');
            }])
            ->latest()
            ->get();
        
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

       $clients = Client::latest()->get();

        $units = Unit::where('status',1)->latest()->get();
        $categories = Category::all();
        $products = Product::where('status',1)->latest()->get();

        $companyInfo = get_company(); 

        $currentMonth = now()->format('m');
        $currentYear = now()->format('y');

        $randomNumber = rand(100000, 999999);

        $lastReference = Project::whereRaw('MONTH(created_at) = ?', [$currentMonth]) 
        ->orderBy('created_at', 'desc') 
        ->first();

        if ($lastReference) {
            preg_match('/(\d{3})$/', $lastReference->reference_no, $matches); 
            $increment = (int)$matches[0] + 1; 
        } else {
            $increment = 1;
        }

        $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);
        $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
        $referance_no = 'BCL-PR-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;
      
        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;
        $productCode = 'PRD' . strtoupper(Str::random(5));

        return view('backend.admin.inventory.project.advance.payment.receipt.create',compact('pageTitle', 'clients', 'ledgerGroups', 'projects','ledgers','referance_no','units','products','vat','tax', 'categories', 'productCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        // Validate the incoming form data
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'receive_amount' => 'required|numeric|min:0',
            'reference_no' => 'required',
            'payment_date' => 'required|date',
        ]);

        //dd($request->all());
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {

            $project = Sale::where('invoice_no', $request->input('invoice_no'))->where('project_id', $request->input('project_id'))->firstOrFail();
            $ledger = Ledger::findOrFail($request->payment_method);

            // Determine payment method
            $paymentDescription = "{$ledger->name}";
            $payment_method = $ledger->type == 'Cash' ? 'Cash' : 'Bank';

            //dd($project);
  
            // Create a new project receipt
            $receipt = ProjectReceipt::create([
                'client_id' => $project->client_id,
                'invoice_no' => $project->invoice_no,
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $payment_method,
                'ledger_id' => $ledger->id,
                'payment_date' => $request->input('payment_date'),
                'bank_account_no' => $request->input('bank_account_no'),
                'cheque_no' => $request->input('cheque_no'),
                'cheque_date' => $request->input('cheque_date'),
                'status' => 'incoming',
                'bank_batch_no' => $request->input('bank_batch_no'),
                'bank_date' => $request->input('bank_date'),
                'bkash_number' => $request->input('bkash_number'),
                'reference_no' => $request->input('reference_no'),
                'bkash_date' => $request->input('bkash_date'),
            ]);

          

            // journal payment project receipt add amount
            $project_amount = $project->total ?? 0; // Get the total project amount

            $cashBankLedger  = $ledger;
            $receivableLedger = Ledger::where('type', 'Receivable')->first();
            $companyInfo = get_company();
            $currentMonth = now()->format('m'); 
            $paymentAmount = $request->input('pay_amount', 0); 

            if ($cashBankLedger && $receivableLedger) {
                // Check if a Journal Voucher exists for this payment transaction
                $journalVoucher = JournalVoucher::where('transaction_code', $project->invoice_no)->first();

                $increment = 1;
                if ($journalVoucher && preg_match('/(\d{3})$/', $journalVoucher->transaction_code, $matches)) {
                    $increment = (int)$matches[0] + 1;
                }

                $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);
                $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
                $transactionCode = 'BCL-V-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement; $increment = 1;
                if ($journalVoucher && preg_match('/(\d{3})$/', $journalVoucher->transaction_code, $matches)) {
                    $increment = (int)$matches[0] + 1;
                }

                $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);
                $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
                $transactionCode = 'BCL-V-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;
            
            
                if (!$journalVoucher) {
                    // Create a new Journal Voucher for Payment Received
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code'  => $project->invoice_no,
                        'transaction_date'  => $request->payment_date,
                        'company_id'       => $companyInfo->id,
                        'branch_id'        => $companyInfo->branch->id,
                        'description'       => 'Invoice Payment Received - First Installment', // ম্যানুয়াল বর্ণনা
                        'status'            => 1, // Pending status
                    ]);
                }
            
                // Payment Received -> Cash & Bank (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $cashBankLedger->id, // নগদ ও ব্যাংক হিসাব
                    'reference_no'       => $project->invoice_no,
                    'description'        => $paymentDescription . ' of ' . number_format($paymentAmount, 2) . ' Taka Received from Customer for Invoice ' . $project->invoice_no,
                    'debit'              => $paymentAmount, // টাকা জমা হচ্ছে
                    'credit'             => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            
                // Payment Received -> Accounts Receivable (Credit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $receivableLedger->id, 
                    'reference_no'       => $project->invoice_no,
                    'description'        => $paymentDescription . ' Accounts Receivable Reduced by '.$paymentAmount.' Taka',
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
            // return redirect()->back()->with('success', 'Payment has been successfully recorded!');

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
