<?php

namespace App\Http\Controllers\Backend;

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
use Illuminate\Http\Request;
use App\Exports\JournalExport;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JournalVoucherImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
            ->where('type',1) // Type 1=>Contra Voucher
            ->where('status',1) // Status 1=>Pending Voucher
            ->latest()->get();

        $totalDebit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('debit');
        });
    
        $totalCredit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('credit');
        });

        return view('backend.admin.voucher.journal.index',compact('pageTitle','journalVouchers','totalDebit','totalCredit'));
    }

    // excel list
    public function excel()
    {
        $pageTitle = 'Journal Excel Entry List';

        // Fetch journal vouchers with related company, branch, and ledger details
        $journalVouchers = JournalVoucher::with(['company', 'branch', 'details.ledger'])
            ->orderBy('id', 'desc')
            ->where('status',0) // Status 0=>Draft/Excel Voucher
            ->get();

        $totalDebit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('debit');
        });
    
        $totalCredit = $journalVouchers->sum(function ($voucher) {
            return $voucher->details->sum('credit');
        });

        return view('backend.admin.voucher.journal.excel',compact('pageTitle','journalVouchers','totalDebit','totalCredit'));
    }

    // update voucher status
    public function updateStatus(Request $request)
    {
        $voucher = JournalVoucher::find($request->voucher_id);

        if ($voucher) {
            $voucher->status = '1';
            $voucher->save();

            return response()->json(['message' => 'ভাউচার সফলভাবে মূল তালিকায় স্থানান্তর করা হয়েছে!']);
        }

        return response()->json(['message' => 'ভাউচার পাওয়া যায়নি!'], 404);
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

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $transactionCode = 'BCL-V-'.$fullDate.' - '.$randomNumber;

        return view('backend.admin.voucher.journal.create',compact('pageTitle','branches','ledgers','transactionCode','companies'));
        
    }

    public function contracreate()
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

        return view('backend.admin.voucher.journal.contracreate',compact('pageTitle','branches','ledgers','transactionCode','companies','cashBankAccounts'));
        
    }

    public function manuallyCreate(){
        $pageTitle = 'Journal Entry';
        $branches = Branch::where('status',1)->latest()->get();
        $companies = Company::where('status',1)->latest()->get();
        $ledgers = Ledger::where('status',1)->latest()->get();

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $transactionCode = 'BCL-V-'.$fullDate.' - '.$randomNumber;

        return view('backend.admin.voucher.journal.manually_create',compact('pageTitle','branches','ledgers','transactionCode','companies'));
    }

    public function manuallyCapitalCreate(){
        $pageTitle = 'Journal Entry';
        $branches = Branch::where('status',1)->latest()->get();
        $companies = Company::where('status',1)->latest()->get();
        $ledgers = Ledger::where('status',1)->latest()->get();

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $transactionCode = 'BCL-V-'.$fullDate.' - '.$randomNumber;

        return view('backend.admin.voucher.journal.manually_capital_create',compact('pageTitle','branches','ledgers','transactionCode','companies'));
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

        //dd($request->all());
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
                return back()->withErrors(['error' => 'No valid ledger entries found.']);
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

    public function capitalstore(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'transaction_code' => 'required|unique:journal_vouchers',
            'company_id' => 'required',
            'branch_id' => 'required',
            'transaction_date' => 'required|date',
            'type' => 'required|array',
        ]);
    
        // First, check if there is any valid data (at least one valid 'type')
        $hasValidData = false;
    
        foreach ($request->type as $i => $type) {
            if (!empty($type) && (!empty($request->debit[$i]) || !empty($request->credit[$i]))) {
                $hasValidData = true;
                break; // If there's at least one valid row, we don't need to check further
            }
        }
    
        // If no valid data is found, return an error response
        if (!$hasValidData) {
            return redirect()->back()->with('error', 'No valid data found. Please ensure at least one entry is valid.');
        }
        $lastMonthLastDate = now()->subMonth()->endOfMonth()->toDateString();
        // Create the JournalVoucher record
        $journalVoucher = JournalVoucher::create([
            'transaction_code' => $request->transaction_code,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'transaction_date' => $lastMonthLastDate,
        ]);

        $ledger_name = $request->ledger[$i]; // Ledger name (e.g., "Petty Cash")
        $ledger_group = $request->group[$i];
        $ledger_subgroup = $request->sub_group[$i];

        $capitalLedger = Ledger::where('name','Capital Account')->first();
    
        // Iterate through each row in the table and create journal voucher details
        foreach ($request->type as $i => $type) {
            // Skip if the row has empty type or both debit and credit are empty
            if (empty($type) || (empty($request->debit[$i]) && empty($request->credit[$i]))) {
                continue; // Skip this iteration
            }
    
            // Get the debit and credit values for the current row
            $debit = $request->debit[$i];
            $credit = $request->credit[$i];
            $ledger_name = $request->ledger[$i]; // Ledger name (e.g., "Petty Cash")
            $ledger_group = $request->group[$i];
            $ledger_subgroup = $request->sub_group[$i];

    
            // Retrieve the ledger ID from the ledger name (assuming the ledger name is unique)
            $ledger = Ledger::where('name', $ledger_name)->first();
    
            if (!$ledger) {
                // If the ledger doesn't exist, skip this row and continue
                continue;
            }
    
            $ledger_id = $ledger->id; // Get the ledger's ID
          
            if ($type == 'Asset') {
                // Debit the selected ledger (Asset Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $ledger_id,
                    'reference_no' => "REF-" . rand(100000, 999999),
                    'description' => 'Opening Balance Entry - Asset ' . $ledger_name,
                    'debit' => $debit,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Credit the capital account (for Asset entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $capitalLedger->id, // Capital Account
                    'reference_no' => "REF-" . rand(100000, 999999),
                    'description' => 'Capital Account Credit for Asset ' . $ledger_name,
                    'debit' => 0,
                    'credit' => $debit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($type == 'Liability') {
                // Credit the selected ledger (Liability Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $ledger_id,
                    'reference_no' => "REF-" . rand(100000, 999999),
                    'description' => 'Opening Balance Entry - Liability ' . $ledger_name,
                    'debit' => 0,
                    'credit' => $credit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Debit the capital account (for Liability entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $capitalLedger->id, // Capital Account
                    'reference_no' => "REF-" . rand(100000, 999999),
                    'description' => 'Capital Account Debit for Liability ' . $ledger_name,
                    'debit' => $credit,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        // Commit the transaction and return success message
        return redirect()->back()->with('success', 'Journal Voucher saved successfully!');
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

        return view('backend.admin.voucher.journal.edit', compact('pageTitle', 'companies', 'branch', 'journal', 'ledgers'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     //dd($request->all());
    //     $request->validate([
    //         'transaction_code' => 'required|unique:journal_vouchers,transaction_code,' . $id,
    //         'company_id' => 'required',
    //         'branch_id' => 'required',
    //         'transaction_date' => 'required|date',
    //     ]);

    //     DB::beginTransaction();

    //     try {
    //         $journalVoucher = JournalVoucher::findOrFail($id);
    //         $journalVoucher->update([
    //             'transaction_code' => $request->transaction_code,
    //             'company_id' => $request->company_id,
    //             'branch_id' => $request->branch_id,
    //             'transaction_date' => $request->transaction_date,
    //         ]);

    //         // Delete old details
    //         JournalVoucherDetail::where('journal_voucher_id', $id)->delete();

    //         $details = [];
    //         $totalDebit = 0;
    //         $totalCredit = 0;

    //         foreach ($request->ledger_id as $index => $ledgerId) {
    //             if (!empty($ledgerId)) {
    //                 $debit = isset($request->debit[$index]) ? (float) $request->debit[$index] : 0;
    //                 $credit = isset($request->credit[$index]) ? (float) $request->credit[$index] : 0;

    //                 $totalDebit += $debit;
    //                 $totalCredit += $credit;

    //                 $details[] = [
    //                     'journal_voucher_id' => $journalVoucher->id,
    //                     'ledger_id' => $ledgerId,
    //                     'reference_no' => $request->reference_no[$index] ?? '',
    //                     'description' => $request->description[$index] ?? '',
    //                     'debit' => $debit,
    //                     'credit' => $credit,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ];
    //             }
    //         }

    //         if ($totalDebit !== $totalCredit) {
    //             return back()
    //                 ->withErrors(['error' => 'Total Debit (৳' . number_format($totalDebit, 2) . ') and Total Credit (৳' . number_format($totalCredit, 2) . ') must be equal.'])
    //                 ->withInput();
    //         }

    //         // Insert updated details
    //         JournalVoucherDetail::insert($details);

    //         DB::commit();

    //         return redirect()->route('journal-voucher.index')->with('success', 'Journal Voucher updated successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => 'An error occurred while updating the journal voucher.']);
    //     }
    // }

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

            return redirect()->route('journal-voucher.index')->with('success', 'Journal Voucher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while updating the journal voucher.']);
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

            return redirect()->back()->with('error', 'Journal Voucher deleted successfully!.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Journal Voucher deleted successfully!.');
        }
    }

    // import download formate
    public function downloadFormat()
    {
        return Excel::download(new JournalExport, 'Journal_Import_Template.xlsx');
    }
 
    // import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Validate file type and size
        ]);
    
        try {
            Excel::import(new JournalVoucherImport, $request->file('file'));
    
            return back()->with('success', 'Journal data imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing file: ' . $e->getMessage());
        }

    
        return back()->with('success', 'Journal data imported successfully!');
    }

}
