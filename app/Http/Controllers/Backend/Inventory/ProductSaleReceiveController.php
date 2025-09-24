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
        $pageTitle = 'Payment Receive List';
    
        $receipts = ProjectReceipt::with(['client'])
            ->orderBy('id', 'desc')
            ->get();
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

        
        return view('backend.admin.inventory.project.payment.receipt.index', compact('pageTitle', 'receipts','ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Receive Payment';

        $customers = Client::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();
        // $projects = Project::where('project_type','Running')->latest()->get();
        // $projects = Project::where('project_type', 'Running')
        //     ->whereColumn('paid_amount', '<', 'grand_total')
        //     ->latest()
        //     ->get();

        // Only fetch projects that have invoices in the 'sales' table
        // $projects = Project::whereHas('sales')->where('project_type', 'Running')->latest()->get();

        $projects = Project::whereHas('sales', function ($query) {
                $query->where(function ($q) {
                    $q->whereColumn('grand_total', '!=', 'paid_amount')
                    ->orWhere('status', '!=', 'Paid');
                });
            })
            ->where('project_type', 'Running')
            ->with(['sales' => function($q) {
                $q->where('status', 'Paid')
                ->select('project_id', DB::raw('SUM(grand_total) as total_grand'), DB::raw('SUM(paid_amount) as total_paid'))
                ->groupBy('project_id');
            }])
            ->latest()
            ->get();
        
        // dd($projects);


    
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();


        return view('backend.admin.inventory.project.payment.receipt.create',compact('pageTitle', 'customers', 'ledgerGroups', 'projects','ledgers'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Validate the incoming form data
        $request->validate([
            // 'client_id' => 'required|exists:clients,id',
            'project_id' => 'required|exists:projects,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        //dd($request->all());
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {

            // $project = Project::where('id', $request->input('project_id'))->first();
            $project = Sale::where('invoice_no', $request->input('invoice_no'))->where('project_id', $request->input('project_id'))->firstOrFail();
            $ledger = Ledger::findOrFail($request->payment_method);

            // if($ledger->type == 'Cash'){
            //     $paymentDescription = "{$ledger->name}";
            //     $payment_method = 'Cash';
            // }elseif($ledger->type == 'Bank'){
            //     $paymentDescription = "{$ledger->name}";
            //     $payment_method = 'Bank';
            // }

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

    public function edit($id)
    {
        $pageTitle = "Edit Receive Payment";
        $receipt = ProjectReceipt::findOrFail($id);

        // Get the sale record for this receipt
        $sale = Sale::where('invoice_no', $receipt->invoice_no)->first();

        // If a sale exists, we can get project_id from it
        $projectId = $sale ? $sale->project_id : null;

        $projects = Project::where(function ($query) use ($projectId) {
                $query->whereHas('sales', function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereColumn('grand_total', '!=', 'paid_amount')
                            ->orWhere('status', '!=', 'Paid');
                    });
                });

                // Always include the project linked to this receipt’s sale
                if ($projectId) {
                    $query->orWhere('id', $projectId);
                }
            })
            ->where('project_type', 'Running')
            ->with(['sales' => function ($q) {
                $q->select(
                    'project_id',
                    DB::raw('SUM(grand_total) as total_grand'),
                    DB::raw('SUM(paid_amount) as total_paid')
                )->groupBy('project_id');
            }])
            ->latest()
            ->get();

        $ledgers = Ledger::whereIn('type', ['Cash', 'Bank'])->get();
        // dd($ledgers);

        return view('backend.admin.inventory.project.payment.receipt.edit', compact(
            'pageTitle',
            'receipt',
            'projects',
            'ledgers',
            'sale'
        ));
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming form data
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        // Begin a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $receipt = ProjectReceipt::findOrFail($id);
            $project = Sale::where('invoice_no', $receipt->invoice_no)
                        ->where('project_id', $request->input('project_id'))
                        ->firstOrFail();
                        
            $ledger = Ledger::findOrFail($request->payment_method);
            
            // Calculate the difference in payment amount for journal adjustment
            $paymentDifference = $request->input('pay_amount') - $receipt->pay_amount;
            // dd($paymentDifference);
            
            // Determine payment method
            $paymentDescription = "{$ledger->name}";
            $payment_method = $ledger->type == 'Cash' ? 'Cash' : 'Bank';
            
            // Store old values for potential rollback
            $oldPayAmount = $receipt->pay_amount;
            $oldDueAmount = $receipt->due_amount;
            
            // Update the receipt
            $receipt->update([
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
                'bank_batch_no' => $request->input('bank_batch_no'),
                'bank_date' => $request->input('bank_date'),
                'bkash_number' => $request->input('bkash_number'),
                'reference_no' => $request->input('reference_no'),
                'bkash_date' => $request->input('bkash_date'),
                'description' => $request->input('description'),
            ]);
            
            // Update journal entries if payment amount changed
            if ($paymentDifference != 0) {
                $cashBankLedger = $receipt->ledger_id; // Use the ledger from the receipt
                // dd($cashBankLedger);
                $receivableLedger = Ledger::where('type', 'Receivable')->first();
                
                if ($cashBankLedger && $receivableLedger) {
                    // Find the journal voucher for this transaction
                    $journalVoucher = JournalVoucher::where('transaction_code', $project->invoice_no)->first();
                    
                    if ($journalVoucher) {
                        // Update or create debit entry (Cash/Bank)
                        $debitEntry = JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                            ->where('ledger_id', $cashBankLedger)
                            ->first();
                            
                        if ($debitEntry) {
                            $debitEntry->update([
                                'debit' => $debitEntry->debit + $paymentDifference,
                                'description' => $paymentDescription . ' of ' . number_format($request->input('pay_amount'), 2) . ' Taka Received from Customer for Invoice ' . $project->invoice_no,
                            ]);
                        }
                        
                        // Update or create credit entry (Receivable)
                        $creditEntry = JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)
                            ->where('ledger_id', $receivableLedger->id)
                            ->first();

                            // dd($creditEntry);
                            
                        if ($creditEntry) {
                            $creditEntry->update([
                                'credit' => $creditEntry->credit + $paymentDifference,
                                'description' => $paymentDescription . ' Accounts Receivable Adjusted by '.$paymentDifference.' Taka',
                            ]);
                        }
                    }
                }
            }
            
            // Update the project's paid amount and status
            if ($project) {
                // Adjust the paid amount by the difference
                $project->paid_amount += $paymentDifference;
                
                // Check if the total paid amount is equal to or greater than the project amount
                if ($project->paid_amount >= $project->grand_total) {
                    $project->status = 'paid';
                } else if ($project->paid_amount > 0) {
                    $project->status = 'partially_paid';
                } else {
                    $project->status = 'due';
                }
                
                $project->save();
            }
            
            // Commit the transaction
            DB::commit();
            
            return redirect()->route('project.receipt.payment.index')->with('success', 'Payment has been successfully updated!');
            
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
            
            // Log the error with details
            Log::error('Payment update failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all(),
            ]);
            
            return redirect()->back()->with('error', 'Payment update failed! ' . $e->getMessage());
        }
    }

    public function view(Request $request)
    {
        $invoice_no = $request->query('invoice_no');

        // dd($invoice_no);

        $pageTitle = 'Payment Receive Details';

        $sale = Sale::where('invoice_no', $invoice_no)
            ->with(['products', 'client'])
            ->first();

        //dd($sale);

        // $sale = Sale::where('id', $id)
        //     ->with(['products', 'client']) // Include supplier details
        //     ->first();

        $project = Project::where('id', $sale->project_id)
            ->with(['client', 'items']) 
            ->first();

        if (!$project) {
            return redirect()->back()->with('error', 'Receipt not found.');
        }

        $project_receipts = ProjectReceipt::where('invoice_no', $invoice_no)->get();

        //dd($project_receipts);

        return view('backend.admin.inventory.project.payment.receipt.view', compact('pageTitle', 'project', 'project_receipts', 'sale'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $receipt = ProjectReceipt::findOrFail($id);

            $project = Sale::where('invoice_no', $receipt->invoice_no)->first();
            // dd($project);

            $journalVoucher = JournalVoucher::where('transaction_code', $receipt->invoice_no)->first();
            // dd($journalVoucher);

            if ($journalVoucher) {
                JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)->delete();
                // $journalVoucher->delete();
            }
            if ($project) {
                $project->paid_amount -= $receipt->pay_amount;
                $project->status = ($project->paid_amount >= $project->total) ? 'paid' : 'pending';
                $project->save();
            }

            $receipt->delete();
            // dd('ok');

            DB::commit();

            return redirect()->back()->with('success', 'Payment receipt deleted successfully, and journal entry updated!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting payment receipt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Failed to delete payment receipt! ' . $e->getMessage());
            // return redirect()->back()->with('success', 'Payment receipt deleted successfully, and journal entry updated!');
        }
    }

}
