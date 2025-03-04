<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{

    public function Out()
    {
        $pageTitle = 'Stock Out List';

        // Load products with stock-out data
        $products = Product::with('stockOuts')->get();

        return view('backend.admin.inventory.sales.stock.out', compact('pageTitle', 'products'));
    }

    public function OutView($id)
    {
        $pageTitle = 'Stock Out List';

        return view('backend.admin.inventory.sales.stock.outView', compact('pageTitle'));
    }

    //purchase
    public function In()
    {
        $pageTitle = 'Stock In List';

        // Load products with stock-in data
        $products = Product::with('stockIns')->get();

        return view('backend.admin.inventory.purchase.stock.in', compact('pageTitle', 'products'));
    }

    public function InView($id)
    {
        $pageTitle = 'Stock In List';

        return view('backend.admin.inventory.purchase.stock.inView', compact('pageTitle'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Stock List';

        //$products = Product::all();

        // Load products with stock-in and stock-out data
        $products = Product::with('stockIns', 'stockOuts')->get();

        return view('backend.admin.inventory.stock.index', compact('pageTitle', 'products'));
    }

    public function view($productId)
    {
        $pageTitle = 'Product Stock';

        // Fetch product with stock records
        $product = Product::with('stockIns', 'stockOuts')->where('id', $productId)->first();

        // Check if product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        // Ensure stockIn and stockOut collections are not null
        $stockInRecords = collect($product->stockIns)->map(function ($stockIn) use ($product) {
            $stockIn->product_name = $product->name;
            $stockIn->type = 'in'; // Mark as StockIn
            return $stockIn;
        });

        $stockOutRecords = collect($product->stockOuts)->map(function ($stockOut) use ($product) {
            $stockOut->product_name = $product->name;
            $stockOut->type = 'out'; // Mark as StockOut
            return $stockOut;
        });

        // Debugging before merge
        // dd($stockInRecords, $stockOutRecords);

        // Merge stock data
        $allRecords = collect()->merge($stockInRecords)->merge($stockOutRecords);

        // Sort records by date
        $allRecords = $allRecords->sortBy('created_at');

        // Calculate totals
        $totalQuantityIn = $allRecords->where('type', 'in')->sum('quantity');
        $totalPriceIn = $allRecords->where('type', 'in')->sum(fn($stockIn) => $stockIn->price * $stockIn->quantity);

        $totalQuantityOut = $allRecords->where('type', 'out')->sum('quantity');
        $totalPriceOut = $allRecords->where('type', 'out')->sum(fn($stockOut) => $stockOut->price * $stockOut->quantity);

        return view('backend.admin.inventory.stock.view', compact(
            'pageTitle',
            'product',
            'allRecords',
            'totalQuantityIn',
            'totalPriceIn',
            'totalQuantityOut',
            'totalPriceOut'
        ));
    }


}
