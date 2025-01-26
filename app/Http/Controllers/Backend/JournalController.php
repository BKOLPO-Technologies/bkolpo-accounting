<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\ExpenseCategory;
use App\Models\Journal;
use App\Models\Company;
use App\Models\Transaction;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Journal Voucher List';

        $vouchers = Transaction::with('journals')->where('status',1)->latest()->get();
        return view('backend.admin.voucher.journal.index',compact('pageTitle','vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Journal Voucher Create';
        $branches = Branch::where('status',1)->latest()->get();
        $companies = Company::where('status',1)->latest()->get();
        $ledgers = ExpenseCategory::where('status',1)->latest()->get();

    $date = now()->format('mY');

    // Generate a random 8-digit number
    $randomNumber = mt_rand(100000, 999999);

    $transactionCode = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.voucher.journal.create',compact('pageTitle','branches','ledgers','transactionCode','companies'));
    }

    public function getBranchesByCompany($companyId)
    {
        // Find the company and load its related branch
        $company = Company::with('branch')->find($companyId);
    
        if ($company && $company->branch) {
            return response()->json([
                'success' => true,
                'branch' => $company->branch, // Single branch data
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'No branch found for the selected company.',
        ]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // Validate the incoming request data
        $validatedData = $request->validate([
            'transaction_code' => 'required',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();
    
            // Create the main transaction record
            $transaction = new Transaction();
            $transaction->transaction_code = $validatedData['transaction_code'];
            $transaction->credit_branch_id = $validatedData['branch_id'];
            $transaction->credit_account_id = $validatedData['company_id'];
            $transaction->transaction_date = $validatedData['transaction_date'];
            $transaction->description = $validatedData['description'];
            $transaction->created_by = auth()->user()->id ?? null;
            $transaction->status = 1; // Default status for Journal Voucher
            $transaction->save();
    
            // Loop through the `ledger_id` array in the request
            if ($request->has('ledger_id') && is_array($request->ledger_id)) {
                foreach ($request->ledger_id as $key => $ledgerId) {
                    // Skip if ledger_id is empty or invalid
                    if (!empty($ledgerId)) {
                        // Create a new Journal entry
                        $transactionLedger = new Journal();
                        $transactionLedger->transaction_id = $transaction->id;
                        $transactionLedger->category_id = $ledgerId;      
                        $transactionLedger->description = $request->description[$key] ?? null; 
                        $transactionLedger->debit = $request->debit[$key] ?? 0;        
                        $transactionLedger->credit = $request->credit[$key] ?? 0;   
                        $transactionLedger->save();                                  
                    }
                }
            }
            // Commit the transaction
            DB::commit();

            return redirect()->route('journal-voucher.index')->with('success','Transaction saved successfully.');

        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
    
            // Log the error for debugging
            Log::error('Error saving transaction: ' . $e->getMessage());

            return redirect()->back()->with('error','An error occurred while saving the transaction. Please try again.');
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
