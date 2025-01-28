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
        $ledgers = Ledger::where('status',1)->latest()->get();
        return view('backend.admin.ledger.group.create',compact('pageTitle','ledgers'));
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
            'ledger_id' => 'required|array',  
            'ledger_id.*' => 'exists:ledgers,id',
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

        // Insert records into the ledger_group_details table for each selected ledger
        $ledgerIds = $request->input('ledger_id');

        foreach ($ledgerIds as $ledgerId) {
            LedgerGroupDetail::create([
                'ledger_group_id' => $ledgerGroup->id,  // Linking the group
                'ledger_id' => $ledgerId,  // Each selected ledger
            ]);
        }

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
        $ledgers = Ledger::where('status',1)->latest()->get();
        // Find the LedgerGroup by ID and eager load the related 'ledgers' if necessary
        $ledgerGroup = LedgerGroup::with('ledgers')->findOrFail($id);
       
        $pageTitle = 'Ledger Group Edit';
        return view('backend.admin.ledger.group.edit', compact('ledgerGroup','ledgers','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Step 1: Validate the incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'ledger_ids' => 'required|array',  // Validate that ledger_ids is an array
            'ledger_ids.*' => 'exists:ledgers,id',  // Ensure each ledger ID exists in the ledgers table
        ]);

        // Step 2: Find the LedgerGroup by ID
        $ledgerGroup = LedgerGroup::findOrFail($id);

        // Step 3: Update the LedgerGroup
        $ledgerGroup->update([
            'group_name' => $request->name,
            'status' => $request->status,
            'updated_by' => Auth::user()->id,  // Assuming you're storing the user who updated
        ]);

        // Step 4: Update the LedgerGroupDetails (delete old ones and insert new ones)
        // First, remove any existing entries
        $ledgerGroup->ledgerGroupDetails()->delete();

        // Then, insert the new ledger IDs
        foreach ($request->ledger_ids as $ledgerId) {
            LedgerGroupDetail::create([
                'ledger_group_id' => $ledgerGroup->id,
                'ledger_id' => $ledgerId,
            ]);
        }

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

        // You can choose to either delete them or keep them.
        LedgerGroupDetail::where('ledger_group_id', $ledgerGroup->id)->delete(); 

        // Step 3: Delete the LedgerGroup itself
        $ledgerGroup->delete();

        return redirect()->route('ledger.group.index')->with('success', 'Ledger Group deleted successfully.');
    }
}
