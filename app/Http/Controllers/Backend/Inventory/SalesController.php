<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Client;
use App\Models\SaleProduct;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Sales List';

        $sales = Sale::with('products')->get(); 
        return view('backend.admin.inventory.sales.index',compact('pageTitle','sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Sales';

        // Generate a random 8-digit number
        $randomNumber = mt_rand(100000, 999999);

        $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.sales.create',compact('pageTitle', 'clients', 'products','invoice_no')); 
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
