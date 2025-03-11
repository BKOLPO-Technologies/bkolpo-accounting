<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Client;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSaleReceiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Project Sale Receive List';
    
        // $receipts = Receipt::with(['ledger', 'client', 'supplier', 'incomingChalan', 'outcomingChalan'])
        //     ->orderBy('payment_date', 'desc')
        //     ->get();
        
        return view('backend.admin.inventory.project.payment.receipt.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Project Receive Payment';

        $customers = Client::latest()->get();
        $ledgerGroups = LedgerGroup::with('ledgers')->latest()->get();

        return view('backend.admin.inventory.project.payment.receipt.create',compact('pageTitle', 'customers', 'ledgerGroups'));
    }
}
