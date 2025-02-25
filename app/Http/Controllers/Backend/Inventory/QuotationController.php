<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Quotation List';

        $quotations = Quotation::OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.quotation.index',compact('pageTitle','quotations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Quotation Create';
        $clients = Client::orderBy('id', 'desc')->get();
        return view('backend.admin.inventory.quotation.create',compact('pageTitle','clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'quotation_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);
    
        Quotation::create([
            'client_id' => $request->client_id,
            'quotation_number' => strtoupper('QTN-' . uniqid()),
            'quotation_date' => $request->quotation_date,
            'total_amount' => $request->total_amount,
            'description' => $request->description,
            'status' => $request->status,
        ]);
    
        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotation = Quotation::with('client')->findOrFail($id);

        $pageTitle = 'Quotation View';
        return view('backend.admin.inventory.quotation.view', compact('quotation','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        $clients = Client::orderBy('id', 'desc')->get();
        $pageTitle = 'Quotation Edit';
        return view('backend.admin.inventory.quotation.edit', compact('quotation','pageTitle','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'total_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);
        
        $quotation = Quotation::findOrFail($id);
        $quotation->client_id = $request->client_id;
        $quotation->total_amount = $request->total_amount;
        $quotation->description = $request->description;
        $quotation->status = $request->status;
        $quotation->save();

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->back()->with('success', 'Quotation deleted successfully!');
    }

}
