<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Supplier;
use App\Models\LedgerGroup;
use App\Models\IncomingChalan;
use App\Models\OutcomingChalan;
use App\Models\LedgerGroupDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class SalePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Payment List';
    
        $payments = Payment::with(['ledger', 'client', 'supplier', 'incomingChalan', 'outcomingChalan'])
        ->orderBy('payment_date', 'desc')
        ->whereNotNull('incoming_chalan_id')
        ->get();
    
        return view('backend.admin.inventory.sales.payment.index', compact('pageTitle', 'payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Payment';

        $incomingChalans = IncomingChalan::latest()->get();
        $clients = Client::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();

        return view('backend.admin.inventory.sales.payment.create',compact('pageTitle','incomingChalans','clients','ledgerGroups')); 
    }

    public function getLedgersByGroup(Request $request)
    {
        $ledgers = LedgerGroupDetail::where('group_id', $request->ledger_group_id)
                    ->with('ledger') // Fetch related Ledger details
                    ->get();
    
        // Transform data to return only necessary fields
        $formattedLedgers = $ledgers->map(function ($item) {
            return [
                'id' => $item->ledger->id,
                'name' => $item->ledger->name
            ];
        });
    
        return response()->json(['ledgers' => $formattedLedgers]);
    }

    public function getChalansByClient(Request $request)
    {
        // Step 1: Find sales where client_id matches
        $sales = Sale::where('client_id', $request->client_id)->pluck('id'); 

        // Step 2: Find Incoming Chalans based on sale_id
        $chalans = IncomingChalan::whereIn('sale_id', $sales)
            ->with('sale') // Ensure related sale invoice is fetched
            ->get();

        // Step 3: Format the response
        $formattedChalans = $chalans->map(function ($chalan) {
            return [
                'id' => $chalan->id,
                'invoice_no' => $chalan->sale->invoice_no ?? 'N/A',
                'total_amount' => $chalan->sale->total ?? 0
            ];
        });

        return response()->json(['chalans' => $formattedChalans]);
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
