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
        ->orderBy('id', 'desc')
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
                'total_amount' => $chalan->sale->total-$chalan->sale->paid_amount ?? 0
            ];
        });

        return response()->json(['chalans' => $formattedChalans]);
    }


    


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming form data
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'incoming_chalan_id' => 'nullable|exists:incoming_chalans,id',
            'total_amount' => 'required|numeric|min:0',
            'pay_amount' => 'required|numeric|min:0',
            'due_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank',
            'payment_date' => 'required|date',
        ]);
    
        // Begin a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Check if the payment for this incoming chalan already exists
            $payment = Payment::where('incoming_chalan_id', $request->input('incoming_chalan_id'))
                              ->where('client_id', $request->input('client_id'))
                              ->first();
    
            // Create a new payment
            $payment = Payment::create([
                'client_id' => $request->input('client_id'),
                'ledger_id' => '1',
                'incoming_chalan_id' => $request->input('incoming_chalan_id'),
                'total_amount' => $request->input('total_amount'),
                'pay_amount' => $request->input('pay_amount'),
                'due_amount' => $request->input('due_amount'),
                'payment_method' => $request->input('payment_method'),
                'payment_date' => $request->input('payment_date'),
            ]);
        
            // Find the sale based on the client ID and incoming chalan (you can adjust this logic based on your relationships)
            $sale = Sale::where('client_id', $request->input('client_id'))->first();


            // If sale exists
            if ($sale) {
                // Update the paid amount
                $sale->paid_amount += $request->input('pay_amount');

                // dd($sale->total,$sale->paid_amount);

                // Check if the total paid amount is equal to or greater than the sale amount
                if ($sale->paid_amount >= $sale->total) {


                    // If fully paid, update status to 'paid'
                    $sale->status = 'paid';
                } else {
                    // dd('not paid');
                    // If partially paid, update status to 'partially_paid'
                    $sale->status = 'partially_paid';
                }

                // Save the updated sale
                $sale->save();
            }
            

    
            // Commit the transaction
            DB::commit();
    
            // Redirect after storing the payment
            return redirect()->route('sale.payment.index')->with('success', 'Payment has been successfully recorded!');
    
        } catch (\Exception $e) {
            // If an error occurs, roll back the transaction
            DB::rollBack();
    
            // Log the error or return a custom error message
            return redirect()->back()->with('error', 'Payment failed! ' . $e->getMessage());
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
