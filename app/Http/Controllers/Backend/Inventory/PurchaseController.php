<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function AdminPurchaseIndex()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Purchase';

        // Generate a random 8-digit number
        $randomNumber = mt_rand(100000, 999999);

        $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend/admin/inventory/purchase/create',compact('pageTitle', 'suppliers', 'products','invoice_no'));
    }

    public function AdminPurchaseCreate()
    {
        
    }

    public function AdminPurchaseStore()
    {
        
    }

    public function AdminPurchaseView()
    {
        
    }

    public function AdminPurchaseEdit()
    {
        
    }

    public function AdminPurchaseUpdate()
    {
        
    }
}
