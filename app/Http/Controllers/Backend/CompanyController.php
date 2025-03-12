<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Ledger;
use App\Models\LedgerGroup;
use App\Models\LedgerSubGroup;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
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
        dd($request->all());
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required',
            'branch_id' => 'required',
        ]);

        // Create the company record
        // $company = Company::create([
        //     'name'          => $request->name,
        //     'branch_id'     => $request->branch_id,
        //     'description'   => $request->description,
        //     'status'        => $request->status,
        //     'created_by'    => Auth::user()->id,
        // ]);


        $lastMonthLastDate = now()->subMonth()->endOfMonth()->toDateString();
        dd($lastMonthLastDate);

        if ($request->has('type')) {
            foreach ($request->type as $key => $type) {
                // Ledger Group Create
                $ledgerGroup = LedgerGroup::create([
                    'company_id' => $company->id,
                    'group_name' => $request->group[$key],
                    'created_by' => Auth::user()->id,
                ]);
        
                // Ledger Sub Group Create
                $ledgerSubGroup = LedgerSubGroup::create([
                    'ledger_group_id' => $ledgerGroup->id,
                    'subgroup_name'   => $request->sub[$key],
                    'created_by'      => Auth::user()->id,
                ]);
        
                // Ledger Entry Create (Check if already exists)
                $ledger = Ledger::firstOrCreate(
                    ['name' => $request->ledger[$key]],
                    [
                        'debit' => $request->ob[$key],
                        'created_by'      => Auth::user()->id,
                    ]
                );
        
                // Pivot Table Entry
                DB::table('ledger_group_subgroup_ledgers')->insert([
                    'group_id'    => $ledgerGroup->id,
                    'sub_group_id'=> $ledgerSubGroup->id,
                    'ledger_id'   => $ledger->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
        

        return redirect()->route('company.index')->with('success', 'Company created successfully.');
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
