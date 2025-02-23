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
        $suppliers = Supplier::all();
        $products = Product::all();
        $pageTitle = 'Admin Purchase';
        return view('backend/admin/inventory/purchase/index',compact('pageTitle', 'suppliers', 'products'));
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
