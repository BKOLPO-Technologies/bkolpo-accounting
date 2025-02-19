<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\LedgerGroup;
use App\Models\LedgerGroupDetail;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Imports\LedgerGroupImport;
use App\Exports\LedgerGroupExport;

class LedgerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger Group List';

        $ledgers = LedgerGroup::latest()->get();
        return view('backend.admin.ledger.group.index',compact('pageTitle','ledgers'));
    }

    /**
     * Show the form for creating a new resource.
     */
   /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Ledger Group Create';
        return view('backend.admin.ledger.group.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        // Group information
        $groupName = $request->input('name');
        $status = $request->input('status');
        $userId = Auth::user()->id;  // Get the current user ID

        // Insert the Ledger Group into the ledger_groups table
        $ledgerGroup = LedgerGroup::create([
            'group_name' => $groupName,
            'status' => $status,
            'created_by' => $userId,
        ]);


        return redirect()->route('ledger.group.index')->with('success', 'Ledger Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ledger = LedgerGroup::findOrFail($id);

        $pageTitle = 'Ledger Group View';
        return view('backend.admin.ledger.group.show', compact('ledger','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the LedgerGroup by ID and eager load the related 'ledgers' if necessary
        $ledgerGroup = LedgerGroup::findOrFail($id);
       
        $pageTitle = 'Ledger Group Edit';
        return view('backend.admin.ledger.group.edit', compact('ledgerGroup','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Step 1: Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Step 2: Find the LedgerGroup by ID
        $ledgerGroup = LedgerGroup::findOrFail($id);

        // Step 5: Return response (redirect back with a success message)
        return redirect()->route('ledger.group.index')->with('success', 'Ledger Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Step 1: Find the LedgerGroup by ID
        $ledgerGroup = LedgerGroup::findOrFail($id);

        // Step 2: Check if the LedgerGroup has related ledgers
        if ($ledgerGroup->ledgers()->exists() || $ledgerGroup->ledgerGroupDetails()->exists()) {
            // If there are related Ledgers or LedgerGroupDetails, prevent deletion and show an error message
            return redirect()->route('ledger.group.index')
                            ->with('error', 'Cannot delete this Ledger Group because it has associated ledgers or ledger details.');
        }

        // Step 3: If no related data, proceed with deletion
        $ledgerGroup->delete();

        return redirect()->route('ledger.group.index')
                     ->with('success', 'Ledger Group deleted successfully.');
    }

    // import download formate
    public function downloadFormat()
    {
        return Excel::download(new LedgerGroupExport, 'Ledger_Group_Import_Template.xlsx');
    }

    // import
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048'
        ]);

        try {
            // Process the import
            Excel::import(new LedgerGroupImport, $request->file('file'));

            // Success message
            return redirect()->back()->with('success', 'Ledger Group imported successfully!');
        } catch (\Exception $e) {
            // Handle errors
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
}
