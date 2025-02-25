<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Quotation;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Work Order List';

        $workorders = WorkOrder::OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.workorder.index',compact('pageTitle','workorders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Work Order Create';
        $quotations = Quotation::all();
        return view('backend.admin.inventory.workorder.create',compact('pageTitle','quotations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'start_date' => 'required|date',
        ]);
    
        $workOrder = new WorkOrder();
        $workOrder->quotation_id = $request->quotation_id;
        $workOrder->start_date = $request->start_date;
        $workOrder->end_date = $request->end_date;
        $workOrder->remarks = $request->remarks;
        $workOrder->status = $request->status;
        $workOrder->save();
    
        return redirect()->route('workorders.index')->with('success', 'Work Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workorder = WorkOrder::with('quotation.client')->findOrFail($id);
        $pageTitle = 'Work Order View';
        return view('backend.admin.inventory.workorder.view', compact('workorder', 'pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $workorder = WorkOrder::findOrFail($id);
        $quotations = Quotation::all();
        $pageTitle = 'Work Order Edit';
        return view('backend.admin.inventory.workorder.edit', compact('workorder','pageTitle','quotations'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:In Progress,Completed,Cancelled',
            'remarks' => 'nullable|string',
        ]);
    
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->start_date = $request->start_date;
        $workOrder->end_date = $request->end_date;
        $workOrder->status = $request->status;
        $workOrder->remarks = $request->remarks;
        $workOrder->save();

        return redirect()->route('workorders.index')->with('success', 'Work Order updated successfully!');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->delete();
    
        return redirect()->back()->with('success', 'Work Order deleted successfully!');
    }

    public function invoice(string $id)
    {
        $workorder = WorkOrder::findOrFail($id);
        $pageTitle = 'Work Order Invoice';

        return view('backend.admin.inventory.workorder.invoice', compact('workorder', 'pageTitle'));
    }

    
}
