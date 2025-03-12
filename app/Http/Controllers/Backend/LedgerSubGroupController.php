<?php

namespace App\Http\Controllers\Backend;

use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Models\LedgerSubGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LedgerSubGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger Sub Group List';

        $ledgerSubGroups = LedgerSubGroup::with('ledgerGroup')->latest()->get();

        return view('backend.admin.ledger.subgroup.index', compact('pageTitle', 'ledgerSubGroups'));
    }

    public function create()
    {
        $pageTitle = 'Ledger Sub Group Create';
        $groups = LedgerGroup::all();

        //dd($groups);

        return view('backend.admin.ledger.subgroup.create', compact('pageTitle', 'groups'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'subgroup_name' => 'required|string|max:255',
            'ledger_group_id' => 'required|exists:ledger_groups,id', // Ensure the group exists
            'status' => 'nullable|in:0,1',
        ]);

        // Get authenticated user ID (if applicable)
        $userId = Auth::id(); 

        // Insert the Ledger Sub Group into the ledger_sub_groups table
        $ledgerSubGroup = LedgerSubGroup::create([
            'subgroup_name' => $validatedData['subgroup_name'],
            'ledger_group_id' => $validatedData['ledger_group_id'],
            'status' => $validatedData['status'] ?? 1, // Default to active if null
            'created_by' => $userId,
        ]);

        return redirect()->route('ledger.sub.group.index')->with('success', 'Ledger Sub Group created successfully.');
    }

    public function destroy($id)
    {
        // Find the Ledger Sub Group
        $subGroup = LedgerSubGroup::findOrFail($id);

        // Delete the record
        $subGroup->delete();

        // Redirect with success message
        return redirect()->route('ledger.sub.group.index')->with('success', 'Ledger Sub Group deleted successfully.');
    }

    public function edit($id)
    {
        // Find the Ledger Sub Group
        $subGroup = LedgerSubGroup::findOrFail($id);

        // Fetch all Ledger Groups for the dropdown
        $groups = LedgerGroup::all();

        $pageTitle = 'Edit Ledger Sub Group';

        return view('backend.admin.ledger.subgroup.edit', compact('subGroup', 'groups', 'pageTitle'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validatedData = $request->validate([
            'subgroup_name' => 'required|string|max:255',
            'ledger_group_id' => 'required|exists:ledger_groups,id',
            'status' => 'required|boolean',
        ]);

        // Find the Ledger Sub Group
        $subGroup = LedgerSubGroup::findOrFail($id);

        // Update the record
        $subGroup->update([
            'subgroup_name' => $request->input('subgroup_name'),
            'ledger_group_id' => $request->input('ledger_group_id'),
            'status' => $request->input('status'),
            'updated_by' => Auth::user()->name, // Store the authenticated user's name
        ]);

        // Redirect with success message
        return redirect()->route('ledger.sub.group.index')->with('success', 'Ledger Sub Group updated successfully.');
    }

}
