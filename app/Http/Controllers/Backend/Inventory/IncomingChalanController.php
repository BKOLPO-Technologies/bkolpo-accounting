<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\IncomingChalan;
use App\Models\IncomingChalanProduct;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class IncomingChalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Incoming Chalan List';
    
        // Fetch all incoming chalans with related sale details
        $incomingchalans = IncomingChalan::with('sale')->latest()->get();
    
        return view('backend.admin.inventory.sales.chalan.index', compact('pageTitle', 'incomingchalans'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        $pageTitle = 'Incoming Chalan';

        $sales = Sale::latest()->get();

        return view('backend.admin.inventory.sales.chalan.create',compact('pageTitle','sales')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        // Validate request data
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'invoice_date' => 'required|date',
            'description' => 'nullable|string',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'receive_quantity' => 'required|array',
        ]);

        // Create IncomingChalan record
        $incomingChalan = IncomingChalan::create([
            'sale_id' => $request->sale_id,
            'invoice_date' => $request->invoice_date,
            'description' => $request->description,
        ]);

        // Insert product details into IncomingChalanProduct table
        foreach ($request->product_id as $index => $productId) {
            IncomingChalanProduct::create([
                'incoming_chalan_id' => $incomingChalan->id,
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
                'receive_quantity' => $request->receive_quantity[$index],
            ]);
        }

        return redirect()->route('incoming.chalan.index')->with('success', 'Incoming Chalan created successfully!');
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
