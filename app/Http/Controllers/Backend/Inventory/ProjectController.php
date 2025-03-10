<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Database\QueryException;
use Exception;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Project List';
    
        $projects = Project::latest() ->with('client')->get();

        return view('backend.admin.inventory.project.index', compact('pageTitle', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Project Create';

        $clients = Client::latest()->get();

        // // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $referance_no = 'BKOLPO-'. $randomNumber;

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $referance_no = 'BCL-PR-'.$fullDate.' - '.$randomNumber;

        return view('backend.admin.inventory.project.create',compact('pageTitle','clients','referance_no')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction(); // Start a database transaction

        try {
            // Validate project data
            $request->validate([
                'project_name' => 'required|string|max:255',
                'project_location' => 'required|string|max:255',
                'project_coordinator' => 'required|string|max:255',
                'client_id' => 'required|exists:clients,id',
                'reference_no' => 'required|string|unique:projects,reference_no',
                'schedule_date' => 'nullable|date',
                'total_discount' => 'nullable|numeric|min:0',
                'total_subtotal' => 'nullable|numeric|min:0',
                'transport_cost' => 'nullable|numeric|min:0',
                'carrying_charge' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'grand_total' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'project_type' => 'required|in:ongoing,upcoming,completed',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'items' => 'required|array',
                'items.*' => 'required|string|max:255',
                'order_unit' => 'required|array',
                'order_unit.*' => 'required|string|max:255',
                'unit_price' => 'required|array',
                'unit_price.*' => 'required|numeric|min:0',
                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',
                'subtotal' => 'required|array',
                'subtotal.*' => 'required|numeric|min:0',
                'discount' => 'nullable|array',
                'discount.*' => 'nullable|numeric|min:0',
                'total' => 'required|array',
                'total.*' => 'required|numeric|min:0',
            ]);
    
            // Store the project in the database
            $project = Project::create([
                'project_name' => $request->project_name,
                'project_location' => $request->project_location,
                'project_coordinator' => $request->project_coordinator,
                'client_id' => $request->client_id,
                'reference_no' => $request->reference_no,
                'schedule_date' => $request->schedule_date,
                'total_discount' => $request->total_discount ?? 0,
                'subtotal' => $request->total_subtotal ?? 0,
                'transport_cost' => $request->transport_cost ?? 0,
                'carrying_charge' => $request->carrying_charge ?? 0,
                'vat' => $request->vat ?? 0,
                'tax' => $request->tax ?? 0,
                'grand_total' => $request->grand_total ?? 0,
                'paid_amount' => $request->paid_amount ?? 0,
                'status' => 'pending',
                'project_type' => $request->project_type,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
            ]);
    
            // Store project items
            foreach ($request->items as $index => $item) {
                ProjectItem::create([
                    'project_id' => $project->id,
                    'items' => $item,
                    'order_unit' => $request->order_unit[$index],
                    'unit_price' => $request->unit_price[$index],
                    'quantity' => $request->quantity[$index],
                    'subtotal' => $request->subtotal[$index],
                    'discount' => $request->discount[$index] ?? 0,
                    'total' => $request->total[$index],
                ]);
            }
    
            DB::commit(); // Commit transaction if everything is successful
    
            return redirect()->route('projects.index')->with('success', 'Project and items created successfully!');
    
        } catch (QueryException $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
    
        } catch (Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
