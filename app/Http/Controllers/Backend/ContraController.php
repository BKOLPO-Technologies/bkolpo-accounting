<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\Company;
use App\Models\Journal;
use Illuminate\View\View;
use App\Models\LedgerGroup;
use App\Models\LedgerSubGroup;
use App\Models\LedgerGroupSubgroupLedger;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exports\JournalExport;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Models\JournalVoucherDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JournalVoucherImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ContraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Contra Entry List';

        // Fetch journal vouchers with related company, branch, and ledger details
        $journalVouchers = JournalVoucher::with(['company', 'branch', 'details.ledger'])
            ->orderBy('id', 'desc')
            ->where('type',2) // Type 1=>Contra Voucher
            ->where('status',1) // Status 1=>Pending Voucher
            ->latest()->get();

        $totalDebit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('debit');
        });
    
        $totalCredit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('credit');
        });

        return view('backend.admin.voucher.contra.index',compact('pageTitle','journalVouchers','totalDebit','totalCredit'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Contra Entry';
        $branches = Branch::where('status',1)->latest()->get();
        $companies = Company::where('status',1)->latest()->get();
        $ledgers = Ledger::where('status',1)->latest()->get();

        $cashBankAccounts = Ledger::whereIn('type', ['Cash', 'Bank'])->where('status',1)->get();
        // dd($cashBankAccounts);

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $transactionCode = 'BCL-V-'.$fullDate.' - '.$randomNumber;

        return view('backend.admin.voucher.contra.create',compact('pageTitle','branches','ledgers','transactionCode','companies','cashBankAccounts'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'transaction_code' => 'required|unique:journal_vouchers',
            'company_id' => 'required',
            'branch_id' => 'required',
            'transaction_date' => 'required|date',
        ]);
        //dd($request->all());
    
        DB::beginTransaction();
    
        try {
            $totalDebit = 0;
            $totalCredit = 0;
            $details = [];

            if (empty(array_filter($request->ledger_id))) {
                //dd('hello');
                // // Send an error message and redirect back if all values are null
                // session()->flash('error', 'At least one ledger entry is required.');    
                // return redirect()->back();
                
                return back()->with('error', 'At least one ledger entry is required.');
            }

            //dd('hello');

            //dd($details);
    
            foreach ($request->ledger_id as $index => $ledgerId) {
                //dd($request->ledger_id);
                if (!empty($ledgerId)) { // Only process non-empty ledger IDs
                    //dd($ledgerId);
                    // $debit = (float) $request->debit[$index] ?? 0;
                    // $credit = (float) $request->credit[$index] ?? 0;
                    $debit = isset($request->debit[$index]) ? (float) $request->debit[$index] : 0;
                    $credit = isset($request->credit[$index]) ? (float) $request->credit[$index] : 0;

                    //dd($ledgerId);
    
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

                    //dd($details);
                }

                //dd('hello');

            }

            // Debugging: Check if $details is being populated correctly
            if (empty($details)) {
                return back()->with(['error' => 'No valid ledger entries found.']);
            }

            //dd($details);

    
            // **Validation: Check if total debit equals total credit**
            if ($totalDebit !== $totalCredit) {
                //dd($totalDebit, $totalCredit);
                return back()
                    ->withErrors(['error' => 'Total Debit (৳' . number_format($totalDebit, 2) . ') and Total Credit (৳' . number_format($totalCredit, 2) . ') must be equal.'])
                    ->withInput();
            }

            //dd('hello');
    
            // **Proceed with JournalVoucher creation if valid**
            $journalVoucher = JournalVoucher::create([
                'transaction_code' => $request->transaction_code,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'transaction_date' => $request->transaction_date,
                'type' => 2, // Type 2 = Contra Voucher
            ]);
    
            // Insert JournalVoucherDetails
            foreach ($details as &$detail) {
                $detail['journal_voucher_id'] = $journalVoucher->id;
            }
            JournalVoucherDetail::insert($details);
    
            DB::commit();
    
            return redirect()->route('contra-voucher.index')->with('success', 'Contra Voucher saved successfully!');
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
     public function edit($id)
    {
        $pageTitle = 'Journal Entry Edit';
        $journal = JournalVoucher::with('details')->findOrFail($id);
        $companies = Company::where('status',1)->latest()->get();
        $branch = Branch::where('status',1)->where('id', $journal->branch_id)->first();
        $ledgers = Ledger::where('status',1)->latest()->get();

        return view('backend.admin.voucher.contra.edit', compact('pageTitle', 'companies', 'branch', 'journal', 'ledgers'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $request->validate([
            'transaction_code' => 'required|unique:journal_vouchers,transaction_code,' . $id,
            'company_id' => 'required',
            'branch_id' => 'required',
            'transaction_date' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $journalVoucher = JournalVoucher::findOrFail($id);
            $journalVoucher->update([
                'transaction_code' => $request->transaction_code,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'transaction_date' => $request->transaction_date,
            ]);

            // Fetch existing details
            $existingDetails = JournalVoucherDetail::where('journal_voucher_id', $id)->get()->keyBy('ledger_id');

            $totalDebit = 0;
            $totalCredit = 0;
            $updatedLedgerIds = [];

            foreach ($request->ledger_id as $index => $ledgerId) {
                if (!empty($ledgerId)) {
                    $debit = isset($request->debit[$index]) ? (float) $request->debit[$index] : 0;
                    $credit = isset($request->credit[$index]) ? (float) $request->credit[$index] : 0;

                    $totalDebit += $debit;
                    $totalCredit += $credit;

                    $updatedLedgerIds[] = $ledgerId;

                    if (isset($existingDetails[$ledgerId])) {
                        // Update existing record
                        $existingDetails[$ledgerId]->update([
                            'reference_no' => $request->reference_no[$index] ?? '',
                            'description' => $request->description[$index] ?? '',
                            'debit' => $debit,
                            'credit' => $credit,
                            'updated_at' => now(),
                        ]);
                    } else {
                        // Insert new record
                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $ledgerId,
                            'reference_no' => $request->reference_no[$index] ?? '',
                            'description' => $request->description[$index] ?? '',
                            'debit' => $debit,
                            'credit' => $credit,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            if ($totalDebit !== $totalCredit) {
                return back()
                    ->withErrors(['error' => 'Total Debit (৳' . number_format($totalDebit, 2) . ') and Total Credit (৳' . number_format($totalCredit, 2) . ') must be equal.'])
                    ->withInput();
            }

            // Remove details that are no longer in the request
            JournalVoucherDetail::where('journal_voucher_id', $id)
                ->whereNotIn('ledger_id', $updatedLedgerIds)
                ->delete();

            DB::commit();

            return redirect()->route('contra-voucher.index')->with('success', 'Contra Voucher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while updating the Contra voucher.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $voucher = JournalVoucher::findOrFail($id);
            $voucher->delete();

            return redirect()->back()->with('error', 'Contra Voucher deleted successfully!.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Contra Voucher deleted successfully!.');
        }
    }
}
