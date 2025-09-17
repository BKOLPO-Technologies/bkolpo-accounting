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
use App\Models\AdvanceProjectReceipt;
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
    
        $receipts = AdvanceProjectReceipt::with(['client'])
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
        // dd($request->all());
        // Validate the incoming form data
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'receive_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
        ]);

        // find project id
        $project = Project::find($request->project_id);
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Determine payment method
            $ledger = Ledger::findOrFail($request->payment_method);
            $paymentDescription = "{$ledger->name}";
            $payment_method = $ledger->type == 'Cash' ? 'Cash' : 'Bank';

            // Create a new project receipt
            $receipt = AdvanceProjectReceipt::create([
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'reference_no' => $project->reference_no,
                'receive_amount' => $request->receive_amount,
                'payment_method' => $payment_method,
                'ledger_id' => $ledger->id,
                'payment_date' => $request->payment_date,
                'bank_account_no' => $request->bank_account_no,
                'cheque_no' => $request->cheque_no,
                'cheque_date' => $request->cheque_date,
                'bank_batch_no' => $request->bank_batch_no,
                'bank_date' => $request->bank_date,
                'bkash_number' => $request->bkash_number,
                'bkash_date' => $request->bkash_date,
            ]);

            // journal payment project receipt add amount
            $project_amount = $request->receive_amount ?? 0; // Get the total project amount

            $cashBankLedger  = $ledger;
            $receivableLedger = Ledger::where('type', 'Receivable')->first();
            $companyInfo = get_company();
            $currentMonth = now()->format('m'); 
            $paymentAmount = $request->input('receive_amount', 0); 

            if ($cashBankLedger && $receivableLedger) {
                // Check if a Journal Voucher exists for this payment transaction
                $journalVoucher = JournalVoucher::where('transaction_code', $project->reference_no)->first();

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
                        'transaction_code'  => $project->reference_no,
                        'transaction_date'  => $request->payment_date,
                        'company_id'       => $companyInfo->id,
                        'branch_id'        => $companyInfo->branch->id,
                        'description'       => 'Advance Payment Received', // ম্যানুয়াল বর্ণনা
                        'status'            => 1, // Pending status
                    ]);
                }
            
                // Payment Received -> Cash & Bank (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $cashBankLedger->id, // নগদ ও ব্যাংক হিসাব
                    'reference_no'       => $project->reference_no,
                    'description'        => 'Advance Payment Received: ' . number_format($paymentAmount, 2) . ' Taka from Customer for Invoice #' . $project->reference_no,
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
                    'description'        => 'Reduce Accounts Receivable by ' . number_format($paymentAmount, 2) . ' Taka for Invoice #' . $project->reference_no,
                    'debit'              => 0,
                    'credit'             => $paymentAmount,  // পাওনা টাকা কমবে
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('project.advance.receipt.payment.index')->with('success', 'Advance Payment has been successfully recorded!');
    
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
    public function show(string $reference_no)
    {
        $pageTitle = 'Advance Payment Receipt Details';

        $receipt = AdvanceProjectReceipt::with(['client', 'ledger'])->where('reference_no', $reference_no)->first();
        $project = Project::with(['client', 'items', 'advancereceipts'])->find($receipt->project_id);

        if (!$receipt) {
            return redirect()->back()->with('error', 'Payment receipt not found!');
        }

        return view('backend.admin.inventory.project.advance.payment.receipt.show', compact('pageTitle', 'receipt','project'));
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
        DB::beginTransaction();

        try {
            $receipt = AdvanceProjectReceipt::findOrFail($id);

            $journalVoucher = JournalVoucher::where('transaction_code', $receipt->reference_no)->first();
            // dd($journalVoucher);

            if ($journalVoucher) {
                JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)->delete();
                $journalVoucher->delete();
            }

            $receipt->delete();

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

    // Project Store from Modal
    public function projectStore(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction(); // Start a database transaction

        try {
            // Validate project data
            $request->validate([
                'project_name' => 'required|string|max:255',
                'project_location' => 'required|string|max:255',
                'project_coordinator' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
                'reference_no' => 'required|string|unique:projects,reference_no',
                'schedule_date' => 'nullable|date',
                'total_discount' => 'nullable|numeric|min:0',
                'total_subtotal' => 'nullable|numeric|min:0',
                'transport_cost' => 'nullable|numeric|min:0',
                'carrying_charge' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'grand_total' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'project_type' => 'required|in:ongoing,Running,upcoming,completed',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'items' => 'required|array',
                'items.*' => 'required|string|max:255',
                'order_unit' => 'required|array',
                'order_unit.*' => 'required|max:255',
                'unit_price' => 'required|array',
                'unit_price.*' => 'required|numeric|min:0',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
                // 'subtotal' => 'required|array',
                // 'subtotal.*' => 'required|numeric|min:0',
                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',
                'total' => 'required|array',
                'total.*' => 'required|numeric|min:0',
            ]);

           

            $tax = $request->include_tax ? $request->tax : 0; 
            $vat = $request->include_vat ? $request->vat : 0; 
    
            // Store the project in the database
            $project = Project::create([
                'project_name' => $request->project_name,
                'project_location' => $request->project_location,
                'project_coordinator' => $request->project_coordinator,
                'client_id' => $request->client_id,
                'reference_no' => $request->reference_no,
                'schedule_date' => $request->schedule_date,
                'total_discount' => $request->total_discount ?? 0,
                'total_netamount' => $request->total_netamount ?? 0,
                'subtotal' => $request->total_subtotal ?? 0,
                'transport_cost' => $request->transport_cost ?? 0,
                'carrying_charge' => $request->carrying_charge ?? 0,
                'vat' => $vat,
                'vat_amount' => $request->vat_amount,
                'tax' => $tax,
                'tax_amount' => $request->tax_amount,
                'grand_total' => $request->grand_total ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => 'pending',
                'project_type' => $request->project_type,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
            ]);

         
    
            // Store project items
            foreach ($request->items as $index => $item) {
                ProjectItem::create([
                    'project_id' => $project->id,
                    'items' => $item,
                    'unit_id' => $request->order_unit[$index],
                    'unit_price' => $request->unit_price[$index],
                    'quantity' => $request->quantity[$index],
                    'subtotal' => $request->subtotal[$index] ?? 0,
                    'discount' => $request->discount[$index] ?? 0,
                    'total' => $request->total[$index],
                    'items_description' => $request->items_description[$index] ?? '',
                ]);
            }

    
            DB::commit(); // Commit transaction if everything is successful
    
            return redirect()->route('project.advance.receipt.payment.create')->with('success', 'Project and items created successfully!');
    
        } catch (QueryException $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    
        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
