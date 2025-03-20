<?php

namespace App\Http\Controllers\Backend;

use Hash;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\Company;
use Illuminate\View\View;
use App\Models\LedgerGroup;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use App\Models\LedgerSubGroup;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\LedgerGroupSubgroupLedger;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Company List';
        $companys = Company::latest()->get();
        return view('backend.admin.company.index',compact('pageTitle','companys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Company Create';
        $branches = Branch::where('status',1)->latest()->get();

        return view('backend.admin.company.create',compact('pageTitle','branches'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction(); // 🔹 Transaction Start

        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'name'       => 'required',
                'branch_id'  => 'required',
            ]);

            $accountNumber = 'BK' . rand(1000000000, 9999999999);

            // Create the company record
            $company = Company::create([
                'name'        => $request->name,
                'branch_id'   => $request->branch_id,
                'description' => $request->description,
                'country' => $request->country,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'post_code' => $request->post_code,
                'email' => $request->email,
                'phone' => $request->phone,
                'status'      => $request->status,
                'account_no'  => $accountNumber,
                'created_by'  => Auth::user()->id,
            ]);

            if ($request->hasFile('logo')) {
                @unlink(public_path('upload/company/' . $company->logo)); // Delete old logo
                $file = $request->file('logo');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('upload/company'), $filename);
                $company->logo = $filename;
            }

            $company->save();

            if ($request->has('type') && !in_array(null, $request->type) && !in_array(null, $request->group)) {
                
                $lastMonthLastDate = now()->subMonth()->endOfMonth()->toDateString(); // 🔹 গত মাসের শেষ তারিখ

                // 🔹 Generate Transaction Code
                $randomNumber = rand(100000, 999999);
                $fullDate = now()->format('d/m/y');
                $transactionCode = 'BCL-O-'.$fullDate.' - '.$randomNumber;

                // 🔹 Create Journal Voucher 
                $journalVoucher = JournalVoucher::create([
                    'transaction_code' => $transactionCode,
                    'company_id'       => $company->id,
                    'branch_id'        => $request->branch_id,
                    'transaction_date' => $lastMonthLastDate,
                ]);

                foreach ($request->type as $key => $type) {
                    // 🔹 Ledger Group Create
                    $ledgerGroup = LedgerGroup::where('group_name',$request->group[$key])->first();
                    if (!$ledgerGroup) {
                        $ledgerGroup = LedgerGroup::create([
                            'company_id' => $company->id,
                            'group_name' => $request->group[$key],
                            'created_by' => Auth::user()->id,
                        ]);
                    }
            
                    // 🔹 Ledger Sub Group Create
                    $ledgerSubGroup = LedgerSubGroup::where('subgroup_name', $request->sub[$key])->first();
                    // 🔹 Ledger Sub Group Create
                    if(!$ledgerSubGroup){
                        $ledgerSubGroup = LedgerSubGroup::create([
                            'ledger_group_id' => $ledgerGroup->id,
                            'subgroup_name'   => $request->sub[$key],
                            'created_by'      => Auth::user()->id,
                        ]);
                    }
            
                    // 🔹 Ledger  Cash Create
                    $ledger = Ledger::where('name', $request->ledger[$key]) // Match the ledger name with request
                        ->first();
                    if(!$ledger){
                        // 🔹 Ledger Entry Create (Check if already exists)
                        $ledger = Ledger::firstOrCreate(
                            ['name' => $request->ledger[$key]],
                            [
                                'opening_balance' => $request->ob[$key] ?? 0, // ✅ ওপেনিং ব্যালেন্স
                                'ob_type'         => $request->ob_type[$key] ?? 'debit', // ✅ ob_type (debit/credit) 
                                'created_by' => Auth::user()->id,
                            ]
                        );
                    }

                    // 🔹 Ledger  Bank Create
                    $ledger = Ledger::where('name', $request->ledger[$key]) // Match the ledger name with request
                    ->first();
                    if(!$ledger){
                        // 🔹 Ledger Entry Create (Check if already exists)
                        $ledger = Ledger::firstOrCreate(
                            ['name' => $request->ledger[$key]],
                            [
                                'opening_balance' => $request->ob[$key] ?? 0, // ✅ ওপেনিং ব্যালেন্স
                                'ob_type'         => $request->ob_type[$key] ?? 'debit', // ✅ ob_type (debit/credit) 
                                'created_by' => Auth::user()->id,
                            ]
                        );
                    }

                    
            
                    // 🔹 LedgerGroupSubgroupLedger Table Entry
                    $existingEntry = LedgerGroupSubgroupLedger::where('group_id', $ledgerGroup->id)
                    ->where('sub_group_id', $ledgerSubGroup->id)
                    ->where('ledger_id', $ledger->id)
                    ->first(); // First entry found with the same group_id, sub_group_id, and ledger_id

                    if (!$existingEntry) {
                        // Only create if no existing entry found
                        LedgerGroupSubgroupLedger::create([
                            'group_id'     => $ledgerGroup->id,
                            'sub_group_id' => $ledgerSubGroup->id,
                            'ledger_id'    => $ledger->id,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }

                    

                    // // 🔹 Determine Debit or Credit based on Type
                    // $openingBalance = $request->ob[$key] ?? 0;
                    // $debit  = ($type == 'Asset') ? $openingBalance : 0;
                    // $credit = ($type == 'Liability') ? $openingBalance : 0;

                    // // 🔹 Journal Entry 
                    // JournalVoucherDetail::create([
                    //     'journal_voucher_id' => $journalVoucher->id,
                    //     'ledger_id'          => $ledger->id,
                    //     'reference_no'       => "REF-" . rand(100000, 999999),
                    //     'description'        => 'Opening Balance Entry',
                    //     'debit'              => $debit,
                    //     'credit'             => $credit,
                    //     'created_at'         => $lastMonthLastDate,
                    //     'updated_at'         => $lastMonthLastDate,
                    // ]);

                    // 🔹 Determine Debit and Credit Amounts based on Type
                    $openingBalance = $request->ob[$key] ?? 0;

                    if ($type == 'Asset') {
                        $debit = $openingBalance;
                        $credit = 0;
                    } elseif ($type == 'Liability') {
                        $debit = 0;
                        $credit = $openingBalance;
                    } else {
                        $debit = 0;
                        $credit = 0;
                    }

                    // 🔹 Check if Asset Group Exists, Otherwise Create
                    $assetGroup = LedgerGroup::where('group_name',$request->group[$key])->first();
                    if (!$assetGroup) {
                        $assetGroup = LedgerGroup::create([
                            'group_name' => 'Assets',
                            'company_id' => $company->id,
                            'created_by' => Auth::user()->id
                        ]);
                    }

                    // 🔹 Check if Liability Group Exists, Otherwise Create
                    $liabilityGroup = LedgerGroup::where('group_name',$request->group[$key])->first();
                    if (!$liabilityGroup) {
                        $liabilityGroup = LedgerGroup::create([
                            'group_name' => 'Liabilities',
                            'company_id' => $company->id,
                            'created_by' => Auth::user()->id
                        ]);
                    }

                    // 🔹 Check if Asset Ledger Cash Exists, Otherwise Create
                    $assetLedger = Ledger::where('name', $request->ledger[$key]) // Match the ledger name with request
                    ->first();
                    if ($assetLedger) {
                        $assetLedger->update([
                            'opening_balance' => $openingBalance,
                            'ob_type'         => 'debit',
                        ]);
                    }else {
                        $assetLedger = Ledger::create([
                            'name'            => 'Cash',
                            'group_id'        => $assetGroup->id,
                            'opening_balance' => $openingBalance,
                            'ob_type'         => 'debit',
                            'created_by'      => Auth::user()->id,
                        ]);
                    }

                    // 🔹 Check if Asset Ledger Bank Exists, Otherwise Create
                    $assetLedger = Ledger::where('name', $request->ledger[$key]) // Match the ledger name with request
                    ->first();
                    if ($assetLedger) {
                        $assetLedger->update([
                            'opening_balance' => $openingBalance,
                            'ob_type'         => 'debit',
                        ]);
                    }else {
                        $assetLedger = Ledger::create([
                            'name'            => 'Bank',
                            'group_id'        => $assetGroup->id,
                            'opening_balance' => $openingBalance,
                            'ob_type'         => 'debit',
                            'created_by'      => Auth::user()->id,
                        ]);
                    }
                    
                    // 🔹 Check if Liability Ledger Exists, Otherwise Create
                    $liabilityLedger = Ledger::where('name', $request->ledger[$key])->first();
                    if ($liabilityLedger) {
                        $liabilityLedger->update([
                            'opening_balance' => $openingBalance,
                            'ob_type'         => $request->ob_type[$key] ?? 'credit',
                        ]);
                    } else {
                        $liabilityLedger = Ledger::create([
                            'name'            => $request->ledger[$key],
                            'group_id'        => $liabilityGroup->id,
                            'opening_balance' => $openingBalance,
                            'ob_type'         => $request->ob_type[$key] ?? 'credit',
                            'created_by'      => Auth::user()->id,
                        ]);
                    }

                    // 🔹 Journal Voucher Entry for Asset
                    JournalVoucherDetail::create(
                        [
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id'          => $assetLedger->id,
                            'reference_no'       => "REF-" . rand(100000, 999999),
                            'description'        => 'Opening Balance Entry - Asset',
                            'debit'              => $debit,
                            'credit'             => 0,
                            'created_at'         => $lastMonthLastDate,
                            'updated_at'         => $lastMonthLastDate,
                        ]
                    );

                    $first_Liabilities = LedgerGroup::where('group_name','Liabilities')->first();
                    if (!$first_Liabilities) {
                        $first_Liabilities = LedgerGroup::create([
                            'company_id' => $company->id,
                            'group_name' => "Liabilities",
                            'created_by' => Auth::user()->id,
                        ]);
                    }

                    // 🔹 Update or Create Capital Account Ledger
                    $capitalLedger = Ledger::updateOrCreate(
                        ['name' => 'Capital Account'],
                        ['group_id' => $liabilityGroup->id,
                        'opening_balance' => $openingBalance, 
                        'ob_type' => 'credit',
                        'created_by' => Auth::user()->id]
                    );

                    //Log::info('Here');
                     // 🔹 LedgerGroupSubgroupLedger Table Entry
                     $existingEntry = LedgerGroupSubgroupLedger::where('group_id', $ledgerGroup->id)
                        ->where('sub_group_id', $ledgerSubGroup->id)
                        ->where('ledger_id', $capitalLedger->id)
                        ->first(); // First entry found with the same group_id, sub_group_id, and ledger_id
 
                     if (!$existingEntry) {
                         // Only create if no existing entry found
                         LedgerGroupSubgroupLedger::create([
                             'group_id'     => $first_Liabilities->id,
                             'sub_group_id' => $ledgerSubGroup->id,
                             'ledger_id'    => $capitalLedger->id,
                             'created_at'   => now(),
                             'updated_at'   => now(),
                         ]);
                     }

                    
                    // 🔹 Journal Voucher Entry for Capital Account
                    JournalVoucherDetail::updateOrCreate(
                        [
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $capitalLedger->id,
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $capitalLedger->id,
                            'reference_no' => "REF-" . rand(100000, 999999),
                            'description' => 'Opening Balance Entry - Capital Account',
                            'debit' => 0,
                            'credit' => $openingBalance,
                            'created_at' => $lastMonthLastDate,
                            'updated_at' => $lastMonthLastDate
                        ]
                        
                    );
                }
            }

            DB::commit(); // 🔹 Transaction Commit (সব ঠিক থাকলে)

            return redirect()->route('company.index')->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // 🔹 কোনো সমস্যা হলে রোলব্যাক
            
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);

        $pageTitle = 'Company View';
        return view('backend.admin.company.show', compact('company','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);

        $pageTitle = 'Company Edit';
        $branches = Branch::where('status',1)->latest()->get();
        return view('backend.admin.company.edit', compact('company','pageTitle','branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'branch_id' => 'required',
        ]);

        $company = Company::findOrFail($id);

        $company->name = $request->input('name');
        $company->branch_id = $request->branch_id;
        $company->status = $request->input('status');
        $company->description = $request->input('description', ''); 
        $company->save();

        return redirect()->route('company.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);
        $company->delete();
        
        return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
    }
}
