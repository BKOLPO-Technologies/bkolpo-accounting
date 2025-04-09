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
                'currency_symbol' => $request->currency_symbol,
                'fiscal_year' => $request->fiscal_year,
                'vat' => $request->vat,
                'tax' => $request->tax,
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
                    
                    // Type Asset/Liability //

                    // 🔹 Journal Voucher Entry for Asset First Entry
                    JournalVoucherDetail::create(
                        [
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id'          => $ledger->id,
                            'reference_no'       => "REF-" . rand(100000, 999999),
                            'description'        => 'Opening Balance Entry - Asset '. $ledger->name,
                            'debit'              => $debit,
                            'credit'             => 0,
                            'created_at'         => $lastMonthLastDate,
                            'updated_at'         => $lastMonthLastDate,
                        ]
                    );

                    // start capital account er khetre entry //
                    $first_Liabilities = LedgerGroup::where('group_name','Liabilities')->first();
                    if (!$first_Liabilities) {
                        $first_Liabilities = LedgerGroup::create([
                            'company_id' => $company->id,
                            'group_name' => "Liabilities",
                            'created_by' => Auth::user()->id,
                        ]);
                    }

                    $capitalLedger = Ledger::where('name','Capital Account')->first();
                    if (!$capitalLedger) {
                        $capitalLedger = Ledger::create([
                            'name' => 'Capital Account',
                            'group_id' => $first_Liabilities->id,
                            'opening_balance' => $openingBalance,
                            'ob_type' => 'credit',
                            'created_by' => Auth::user()->id,
                        ]);
                    }

                    // 🔹 Ledger Sub Group Create
                    $ledgerSubGroup = LedgerSubGroup::where('subgroup_name','Current Liabilities')->first();
                    // 🔹 Ledger Sub Group Create
                    if(!$ledgerSubGroup){
                        $ledgerSubGroup = LedgerSubGroup::create([
                            'ledger_group_id' => $first_Liabilities->id,
                            'subgroup_name'   => 'Current Liabilities',
                            'created_by'      => Auth::user()->id,
                        ]);
                    }

                    // 🔹 LedgerGroupSubgroupLedger Table Entry
                    $existingEntry = LedgerGroupSubgroupLedger::where('group_id', $first_Liabilities->id)
                        ->where('sub_group_id', $ledgerSubGroup->id)
                        ->where('ledger_id', $capitalLedger->id)
                        ->first(); // First entry found with the same group_id, sub_group_id, and ledger_id
                    // dd($existingEntry);

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
                            'description' => 'Opening Balance Entry - Capital Account'. $ledger->name,
                            'debit' => 0,
                            'credit' => $openingBalance,
                            'created_at' => $lastMonthLastDate,
                            'updated_at' => $lastMonthLastDate
                        ]
                        
                    );
                    // end capital account er khetre entry //
                    // 🔹 Journal Voucher Entry for Asset Second Entry
            
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
        $company->currency_symbol = $request->input('currency_symbol');
        $company->fiscal_year = $request->input('fiscal_year');
        $company->vat = $request->input('vat');
        $company->tax = $request->input('tax');
        $company->description = $request->input('description', '');
        $company->country = $request->input('country', '');
        $company->address = $request->input('address', '');
        $company->city = $request->input('city', '');
        $company->state = $request->input('state', '');
        $company->post_code = $request->input('post_code', '');
        $company->email = $request->input('email', '');
        $company->phone = $request->input('phone', '');
        $company->account_no = $request->input('account_no', '');
        $company->currency_symbol = $request->input('currency_symbol', '');
        $company->fiscal_year = $request->input('fiscal_year', '');

        if ($request->hasFile('logo')) {
            @unlink(public_path('upload/company/' . $company->logo)); // Delete old logo
            $file = $request->file('logo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/company'), $filename);
            $company->logo = $filename;
        }

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
