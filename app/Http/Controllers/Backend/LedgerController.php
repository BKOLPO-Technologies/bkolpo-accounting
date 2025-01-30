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
use App\Traits\SumLedgerAmounts;

class LedgerController extends Controller
{
    use SumLedgerAmounts;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger List';

        $ledgers = Ledger::with(['journalVoucherDetails'])->get();

        $ledgers->each(function ($ledger) {
            $ledger->ledgerSums = $this->getLedgerSums($ledger);
        });

        $totals = $this->getTotalSums($ledgers);
        
        return view('backend.admin.ledger.index',compact('pageTitle','ledgers','totals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Ledger Create';
        $groups = LedgerGroup::where('status',1)->latest()->get();
        return view('backend.admin.ledger.create',compact('pageTitle','groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        // dd($request->all());
        // Validate the incoming request
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'required|array',
            'group_id.*' => 'exists:ledger_groups,id'
        ]);
        
        // Create the Ledger record
        $ledger = Ledger::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        // Attach groups to Ledger
        foreach ($request->group_id as $groupId) {
            LedgerGroupDetail::create([
                'ledger_id' => $ledger->id,
                'group_id' => $groupId
            ]);
        }

        return redirect()->route('ledger.index')->with('success', 'Ledger created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ledger = Ledger::findOrFail($id);

        $pageTitle = 'Ledger View';
        return view('backend.admin.ledger.show', compact('ledger','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ledger = Ledger::with('groups')->findOrFail($id);
        $groups = LedgerGroup::where('status', 1)->latest()->get();
        $pageTitle = 'Ledger Edit';
        return view('backend.admin.ledger.edit', compact('ledger','groups','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'required|array',
            'group_id.*' => 'exists:ledger_groups,id'
        ]);


        $ledger = Ledger::findOrFail($id);

        $ledger->name = $request->input('name');
        $ledger->status = $request->input('status');
        $ledger->save();

        // Sync groups in ledger_group_details
        $ledger->groups()->sync($request->group_id);

        return redirect()->route('ledger.index')->with('success', 'Ledger updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ledger = Ledger::findOrFail($id);
    
        // Remove all related groups
        $ledger->groups()->detach();
    
        // Delete ledger
        $ledger->delete();
        
        return redirect()->route('ledger.index')->with('success', 'Ledger deleted successfully.');
    }
}
