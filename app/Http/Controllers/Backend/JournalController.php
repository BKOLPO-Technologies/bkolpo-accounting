<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\LedgerGroup;
use App\Models\Journal;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
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
        $pageTitle = 'Journal Entry List';

        // Fetch journal vouchers with related company, branch, and ledger details
        $journalVouchers = JournalVoucher::with(['company', 'branch', 'details.ledger'])
            ->orderBy('id', 'desc')
            ->get();

        $totalDebit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('debit');
        });
    
        $totalCredit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('credit');
        });

        return view('backend.admin.voucher.journal.index',compact('pageTitle','journalVouchers','totalDebit','totalCredit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Journal Entry';
        $branches = Branch::where('status',1)->latest()->get();
        $companies = Company::where('status',1)->latest()->get();
        $ledgers = Ledger::where('status',1)->latest()->get();

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
        // Validate the request
        $request->validate([
            'transaction_code' => 'required|unique:journal_vouchers',
            'company_id' => 'required',
            'branch_id' => 'required',
            'transaction_date' => 'required|date',
        ]);
    
        DB::beginTransaction();
    
        try {
            $totalDebit = 0;
            $totalCredit = 0;
            $details = [];

            if (empty(array_filter($request->ledger_id))) {
                // Send an error message and redirect back if all values are null
                session()->flash('error', 'At least one ledger entry is required.');
                return redirect()->back();
            }
    
            foreach ($request->ledger_id as $index => $ledgerId) {
                if (!empty($ledgerId)) { // Only process non-empty ledger IDs
                    $debit = (float) $request->debit[$index] ?? 0;
                    $credit = (float) $request->credit[$index] ?? 0;
    
                    $totalDebit += $debit;
                    $totalCredit += $credit;
    
                    $details[] = [
                        'ledger_id' => $ledgerId,
                        'reference_no' => $request->reference_no[$index] ?? '',
                        'description' => $request->description[$index] ?? '',
                        'debit' => $debit,
                        'credit' => $credit,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            // **Validation: Check if total debit equals total credit**
            if ($totalDebit !== $totalCredit) {
                return back()
                    ->withErrors(['error' => 'Total Debit (৳' . number_format($totalDebit, 2) . ') and Total Credit (৳' . number_format($totalCredit, 2) . ') must be equal.'])
                    ->withInput();
            }
            
            // if ($totalDebit !== $totalCredit) {
            //     return back()->withErrors(['error' => 'Total Debit (৳' . number_format($totalDebit, 2) . ') and Total Credit (৳' . number_format($totalCredit, 2) . ') must be equal.']);
            // }
    
            // **Proceed with JournalVoucher creation if valid**
            $journalVoucher = JournalVoucher::create([
                'transaction_code' => $request->transaction_code,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'transaction_date' => $request->transaction_date,
            ]);
    
            // Insert JournalVoucherDetails
            foreach ($details as &$detail) {
                $detail['journal_voucher_id'] = $journalVoucher->id;
            }
            JournalVoucherDetail::insert($details);
    
            DB::commit();
    
            return redirect()->route('journal-voucher.index')->with('success', 'Journal Voucher saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return back()->withErrors(['error' => 'An error occurred while saving the journal voucher.']);
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
