<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stock List';
    
        $purchases = Purchase::all(); 
    
        return view('backend.admin.inventory.stock.index', compact('pageTitle', 'purchases'));
    }

    public function view($id)
    {
        $pageTitle = 'Stock';
    
        $purchase = Purchase::where('id', $id)->first(); 
    
        return view('backend.admin.inventory.stock.view', compact('pageTitle', 'purchase'));
    }
}
