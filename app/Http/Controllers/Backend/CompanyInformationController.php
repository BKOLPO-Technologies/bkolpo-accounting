<?php

namespace App\Http\Controllers\Backend;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\LedgerExport;
use App\Exports\JournalExport;
use App\Exports\LedgerDataExport;
use App\Exports\JournalDataExport;
use App\Imports\LedgerGroupImport;
use App\Models\CompanyInformation;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JournalVoucherImport;
use App\Exports\ledgerGroupDataExport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CompanyInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Configuration';
        $company = CompanyInformation::first();
        return view('backend.admin.company-information.index', compact('pageTitle','company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $company = CompanyInformation::findOrFail($id);
        // dd($company);

        $company->company_name  = $request->company_name;
        $company->company_branch= $request->company_branch;
        $company->country       = $request->country;
        $company->address       = $request->address;
        $company->city          = $request->city;
        $company->country       = $request->country;
        $company->state         = $request->state;
        $company->post_code     = $request->post_code;
        $company->phone         = $request->phone;
        $company->email         = $request->email;
        $company->updated_by    = Auth::user()->name;
        $company->save();

        if ($request->hasFile('logo')) {
            @unlink(public_path('upload/company/' . $company->logo)); // Delete old logo
            $file = $request->file('logo');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/company'), $filename);
            $company->logo = $filename;
        }

        $company->save();

        return redirect()->back()->with('success', 'Company information Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    // import
    public function import()
    {
        $pageTitle = 'All Import';
        return view('backend.admin.company-information.import', compact('pageTitle'));
    }

    public function export()
    {
        $pageTitle = 'All Export';
        return view('backend.admin.company-information.export', compact('pageTitle'));
    }

    public function ledgerExport()
    {
        return Excel::download(new LedgerDataExport, 'ledgerData.xlsx');
    }

    public function ledgerGroupExport()
    {
        return Excel::download(new ledgerGroupDataExport, 'ledgerGroupData.xlsx');
    }

    public function journalExport()
    {
        return Excel::download(new JournalDataExport, 'journalData.xlsx');
    }
}
