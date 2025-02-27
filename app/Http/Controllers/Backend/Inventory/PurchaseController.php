<?php

namespace App\Http\Controllers\Backend\Inventory;

use DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Purchase List';

        $purchases = Purchase::with('products')->OrderBy('id','desc')->get(); 
        //dd($purchases);
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
    public function AdminPurchaseStore(Request $request)
    {

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
        $discounts = explode(',', $request->input('discounts'));  // Array of discounts

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
            $purchase->description = $request->description;
            $purchase->save();
        
            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $quantities[$index];
                $price = $prices[$index];
                $discount = $discounts[$index];
        
                // Insert into purchase_product table
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->purchase_id = $purchase->id; // Link to the purchase
                $purchaseProduct->product_id = $productId; // Product ID
                $purchaseProduct->quantity = $quantity; // Quantity
                $purchaseProduct->price = $price; // Price
                $purchaseProduct->discount = $discount; // Discount
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


    public function AdminPurchaseView($id)
    {
        $pageTitle = 'Purchase View';

        $purchase = Purchase::where('id', $id)
            ->with(['products', 'supplier']) // Include supplier details
            ->first();

        return view('backend.admin.inventory.purchase.view',compact('pageTitle', 'purchase'));
    }

    public function Print()
    {
        $pageTitle = 'Purchase View';

        // $purchase = Purchase::where('id', $id)
        //     ->with(['products', 'supplier']) // Include supplier details
        //     ->first();

        return view('backend.admin.inventory.purchase.print',compact('pageTitle'));
    }

    public function AdminPurchaseEdit($id)
    {
        $pageTitle = 'Purchase Edit';

        // $purchase = Purchase::where('id', $id)->with('products')->first();
        // Fetch purchase details with supplier and products
        $purchase = Purchase::where('id', $id)
            ->with(['products', 'supplier']) // Include supplier details
            ->first();

        //dd($purchase);
        
        if ($purchase->invoice_date) {
            $purchase->invoice_date = Carbon::parse($purchase->invoice_date);
        }

        $subtotal = $purchase->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity - $product->pivot->discount;
        });

        $suppliers = Supplier::orderBy('id', 'desc')->get();
        $products = Product::where('status',1)->latest()->get();

        return view('backend.admin.inventory.purchase.edit',compact('pageTitle', 'purchase', 'suppliers', 'products', 'subtotal'));
    }

    public function AdminPurchaseUpdate(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no,' . $id, // Allow current invoice_no
            'invoice_date' => 'required|date',
            'subtotal' => 'required|numeric',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            'product_ids' => 'required|not_in:',  // Ensure at least one product is selected
        ]);

        // Extract product data from request
        $productIds = explode(',', $request->input('product_ids'));  
        $quantities = explode(',', $request->input('quantities'));  
        $prices = explode(',', $request->input('prices'));  
        $discounts = explode(',', $request->input('discounts'));  

        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            return back()->with('error', 'At least one product must be selected.');
        }

        try {
            \DB::beginTransaction();

            // Find the existing purchase record
            $purchase = Purchase::findOrFail($id);
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = $validated['invoice_date'];
            $purchase->subtotal = $validated['subtotal'];
            $purchase->discount = $validated['discount'];
            $purchase->total = $validated['total'];
            $purchase->description = $request->description;
            $purchase->save();

            // Remove existing purchase product records and update with new ones
            PurchaseProduct::where('purchase_id', $id)->delete();

            // Insert updated product data
            foreach ($productIds as $index => $productId) {
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->purchase_id = $purchase->id;
                $purchaseProduct->product_id = $productId;
                $purchaseProduct->quantity = $quantities[$index];
                $purchaseProduct->price = $prices[$index];
                $purchaseProduct->discount = $discounts[$index];
                $purchaseProduct->save();
            }

            \DB::commit();

            return redirect()->route('admin.purchase.index')->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Something went wrong! Please try again.']);
        }
    }


    public function destroy(string $id)
    {
        // Find the purchase by its ID
        $purchase = Purchase::find($id);
        

        if ($purchase) {
            // Detach the related PurchaseProduct records (pivot table entries)
            $purchase->products()->detach();
    
            // Delete the sale itself
            $purchase->delete();
    
            return redirect()->back()->with('success', 'Purchase and related products deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Purchase and related products deleted successfully..');
        
    }

    public function getInvoiceDetails($id)
    {
        $purchase = Purchase::with(['supplier', 'purchaseProducts.product'])->find($id);
    
        if (!$purchase) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    
        // Map Purchase Products to extract necessary product details
        $products = $purchase->purchaseProducts->map(function ($purchaseProduct) {
            return [
                'id' => $purchaseProduct->product->id,
                'name' => $purchaseProduct->product->name,
                'price' => $purchaseProduct->price,
                'quantity' => $purchaseProduct->quantity,
                'discount' => $purchaseProduct->discount,
                'stockqty' => $purchaseProduct->product->quantity,
            ];
        });
    
        return response()->json([
            'supplier' => [
                'name' => $purchase->supplier->name,
                'company' => $purchase->supplier->company,
                'phone' => $purchase->supplier->phone,
                'email' => $purchase->supplier->email,
            ],
            'products' => $products, // Properly passing the products array
        ]);
    }

}
