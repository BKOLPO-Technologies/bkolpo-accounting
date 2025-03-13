<?php

namespace App\Http\Controllers\Backend;

use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Ledger;
use Illuminate\View\View;
use App\Models\LedgerGroup;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\LedgerExport;
use App\Models\LedgerSubGroup;
use App\Traits\SumLedgerAmounts;
use App\Models\LedgerGroupDetail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    use SumLedgerAmounts;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger List';

        $ledgers = Ledger::with(['groups', 'journalVoucherDetails'])->get();

        //dd($ledgers);

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
        //dd($request->all());
        // Validate the incoming request
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            //'group_id' => 'required|array',
            'group_id' => 'required',
            //'group_id.*' => 'exists:ledger_groups,id'
            'sub_group_id' => 'required|exists:ledger_sub_groups,id',
            'status' => 'required|integer',
        ]);
        
        // Create the Ledger record
        $ledger = Ledger::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'debit'        => $request->debit,
            'credit'        => $request->credit,
            'created_by'    => Auth::user()->id,
        ]);

        // // Attach groups to Ledger
        // foreach ($request->group_id as $groupId) {
        //     LedgerGroupDetail::create([
        //         'ledger_id' => $ledger->id,
        //         'group_id' => $groupId
        //     ]);
        // }

        // 🔹 Pivot Table Entry
        DB::table('ledger_group_subgroup_ledgers')->insert([
            'group_id'     => $request->group_id, // Using directly from request
            'sub_group_id' => $request->sub_group_id,
            'ledger_id'    => $ledger->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

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
        // Find the Ledger Sub Group
        $subGroup = LedgerSubGroup::findOrFail($id);
        
        return view('backend.admin.ledger.edit', compact('ledger','groups','pageTitle','subGroup'));
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
        $ledger->debit = $request->input('debit');
        $ledger->credit = $request->input('credit');
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
        // Step 1: Find the Ledger by ID
        $ledger = Ledger::findOrFail($id);

        // Step 2: Check if the Ledger has any related JournalVoucherDetail
        if ($ledger->journalVoucherDetails()->exists()) {
            // If JournalVoucherDetails exist, you cannot delete the ledger directly
            return redirect()->route('ledger.index')
                            ->with('error', 'Cannot delete this Ledger because it has related Journal Voucher entries. Please delete the journal entries first.');
        }

        // Step 3: If no related JournalVoucherDetails, proceed with detaching the groups
        $ledger->groups()->detach();

        // Step 4: Delete the ledger
        $ledger->delete();

        return redirect()->route('ledger.index')->with('success', 'Ledger deleted successfully.');
    }
    // import download formate
    public function downloadFormat()
    {
        return Excel::download(new LedgerExport, 'Ledger_Import_Template.xlsx');
    }

    // import
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        $file = $request->file('file');
        $data = Excel::toArray([], $file);
        $rows = $data[0]; // Get all rows from the first sheet (index 0)
    
        // Skip the first row, which contains headers
        $header = $rows[0]; // The first row (headers)
        $rows = array_slice($rows, 1); // Remove the first row from data

        // Find the actual column name containing "Group Name"
        $groupColumn = null;
        foreach ($header as $col) {
            if (str_contains($col, 'Group Name')) { // Match dynamically
                $groupColumn = $col;
                break;
            }
        }
      
        // Loop through the rows and trim spaces around the keys and values
        foreach ($rows as $row) {
            // Map column headers to keys for easy access
            $row = array_combine($header, $row); // Combine header names with data rows
            // dd($row);
            // Create ledger
            $ledger = Ledger::create([
                'name' => $row['Ledger Name'], // Access the correct column
                'debit' => $row['Opening Balance'],
                'credit' => $row['Ending Balance'],
            ]);
            // Attach ledger to group(s)
            if (!empty($row[$groupColumn])) {
                foreach (explode(',', $row[$groupColumn]) as $groupId) {
                    LedgerGroupDetail::create([
                        'ledger_id' => $ledger->id,
                        'group_id' => trim($groupId),
                    ]);
                }
            }
        }
    
        return back()->with('success', 'Ledger data imported successfully!');
    }


}
