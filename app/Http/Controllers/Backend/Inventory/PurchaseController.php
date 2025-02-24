<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Purchase List';

        $purchases = Purchase::with('products')->get(); 
        return view('backend.admin.inventory.purchase.index',compact('pageTitle','purchases'));

        
    }

    public function AdminPurchaseCreate()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $pageTitle = 'Purchase';

        // Generate a random 8-digit number
        $randomNumber = mt_rand(100000, 999999);

        $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend/admin/inventory/purchase/create',compact('pageTitle', 'suppliers', 'products','invoice_no')); 
    }

    // purchase store
    public function AdminPurchaseStore(Request $request){

        // dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no',
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required|not_in:',  // Ensure at least one product is selected
        ]);


        // dd($validated);

        // Access product data from the request
        $productIds = explode(',', $request->input('product_ids'));  // Array of product IDs
        $quantities = explode(',', $request->input('quantities'));  // Array of quantities
        $prices = explode(',', $request->input('prices'));  // Array of prices

        // Check if at least one product is selected
        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            // If no product is selected, return an error message
            return back()->with('error', 'At least one product must be selected.');
        }

        try {
            // Start the transaction
            \DB::beginTransaction();
        
            // Create a new purchase record
            $purchase = new Purchase();
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = $validated['invoice_date'];
            $purchase->subtotal = $validated['subtotal'];
            $purchase->discount = $validated['discount'];
            $purchase->total = $validated['total'];
            $purchase->save();
        
            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $quantities[$index];
                $price = $prices[$index];
        
                // Insert into purchase_product table
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->purchase_id = $purchase->id; // Link to the purchase
                $purchaseProduct->product_id = $productId; // Product ID
                $purchaseProduct->quantity = $quantity; // Quantity
                $purchaseProduct->price = $price; // Price
                $purchaseProduct->save(); // Save the record
            }
        
            // Commit the transaction
            \DB::commit();
        
            // Redirect back with a success message
            return redirect()->route('admin.purchase.index')->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            \DB::rollback();
        
            // Return with error message
            return back()->withErrors(['error' => 'Something went wrong! Please try again.']);
        }
    
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
