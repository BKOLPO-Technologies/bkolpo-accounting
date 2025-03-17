<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;

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
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->latest()->get();
        $pageTitle = 'Purchase';

        // Get current timestamp in 'dmyHis' format (day, month, year)
        $randomNumber = rand(100000, 999999);
        $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        $invoice_no = 'BCL-PO-'.$fullDate.' - '.$randomNumber;

        // // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.purchase.create',compact('pageTitle', 'suppliers', 'products','categories','projects','invoice_no')); 
    }

    // purchase store
    public function AdminPurchaseStore(Request $request)
    {
        // dd($request->all());

        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no',
            // 'invoice_date' => 'required|date',
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
            DB::beginTransaction();

            if($request->category_id == 'all'){
                $categoryId = null;
            }else{
                $categoryId = $request->category_id;
            }
        

            // dd($categoryId);
            // Create a new purchase record
            $purchase = new Purchase();
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = now()->format('Y-m-d');
            $purchase->subtotal = $validated['subtotal'];
            $purchase->discount = $validated['discount'];
            $purchase->total = $validated['total'];
            $purchase->description = $request->description;
            $purchase->category_id = $categoryId;
            $purchase->project_id = $request->project_id;
            $purchase->save();

            // dd('ok');
           
        
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
            
            // Step 2: Get purchase amount
            $purchase_amount = $purchase->total ?? 0; // If purchase doesn't have amount, default to 0

           
            // Step 3: Retrieve Purchase ledger
            $purchasesLedger = Ledger::where('name', 'Purchases')->first();
            $payableLedger = Ledger::where('name', 'Accounts Payable')->first();

            // dd($purchasesLedger,$payableLedger);

            if ($purchasesLedger && $payableLedger) {
                // Check if a Journal Voucher exists for this purchase transaction
                $journalVoucher = JournalVoucher::where('transaction_code', $purchase->invoice_no)->first();

                if (!$journalVoucher) {
                    // Create a new Journal Voucher for Purchase Invoice
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code'  => $purchase->invoice_no,
                        'transaction_date'  => now()->format('Y-m-d'),
                        'description'       => 'Purchase Invoice Recorded - Supplier',
                        'status'            => 1, // Pending status
                    ]);
                }

                // Purchase -> Purchases Account (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $purchasesLedger->id, // Purchases Ledger
                    'reference_no'       => $purchase->invoice_no ?? '',
                    'description'        => 'Purchased Goods from Supplier',
                    'debit'              => $purchase_amount, // পণ্য ক্রয় ব্যয়
                    'credit'             => 0,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                // Purchase Payable -> Accounts Payable (Credit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $payableLedger->id, // Accounts Payable Ledger
                    'reference_no'       => $purchase->invoice_no  ?? '',
                    'description'        => 'Supplier Payable Recorded for Purchase',
                    'debit'              => 0,
                    'credit'             => $purchase_amount, // দেনা বৃদ্ধি পাবে
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }
        
            // Commit the transaction
            DB::commit();
        
            // Redirect back with a success message
            return redirect()->route('admin.purchase.index')->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            DB::rollback();
        
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
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->latest()->get();

        return view('backend.admin.inventory.purchase.edit',compact('pageTitle', 'purchase', 'suppliers', 'products','categories','projects', 'subtotal'));
    }

    public function AdminPurchaseUpdate(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no,' . $id, // Allow current invoice_no
            // 'invoice_date' => 'required|date',
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

            if($request->category_id == 'all'){
                $categoryId = null;
            }else{
                $categoryId = $request->category_id;
            }

            // Find the existing purchase record
            $purchase = Purchase::findOrFail($id);
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = now()->format('Y-m-d');
            $purchase->subtotal = $validated['subtotal'];
            $purchase->discount = $validated['discount'];
            $purchase->total = $validated['total'];
            $purchase->description = $request->description;
            $purchase->category_id = $categoryId;
            $purchase->project_id = $request->project_id;
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

            // // Delete related payments where invoice_no matches
            // Payment::where('invoice_no', $purchase->invoice_no)->delete();

            // Delete related payments using the defined relationship
            $purchase->payments()->delete();

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
        Log::info("Fetching invoice details for ID: {$id}");

        $purchase = Purchase::with(['supplier', 'purchaseProducts.product'])->find($id);

        if (!$purchase) {
            Log::error("Invoice not found for ID: {$id}");
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        Log::info("Purchase record found:", ['purchase_id' => $purchase->id]);

        // Log supplier details
        Log::debug("Supplier Details:", [
            'id' => $purchase->supplier->id,
            'name' => $purchase->supplier->name,
            'company' => $purchase->supplier->company,
            'phone' => $purchase->supplier->phone,
            'email' => $purchase->supplier->email,
        ]);

        // Map Purchase Products to extract necessary product details
        $products = $purchase->purchaseProducts->map(function ($purchaseProduct) {
            Log::debug("Processing product:", [
                'id' => $purchaseProduct->product->id,
                'name' => $purchaseProduct->product->name,
                'price' => $purchaseProduct->price,
                'quantity' => $purchaseProduct->quantity,
                'discount' => $purchaseProduct->discount,
                'stockqty' => $purchaseProduct->product->quantity,
            ]);

            return [
                'id' => $purchaseProduct->product->id,
                'name' => $purchaseProduct->product->name,
                'price' => $purchaseProduct->price,
                'quantity' => $purchaseProduct->quantity,
                'discount' => $purchaseProduct->discount,
                'stockqty' => $purchaseProduct->product->quantity,
            ];
        });

        Log::info("Successfully retrieved invoice details for ID: {$id}");

        return response()->json([
            'supplier' => [
                'name' => $purchase->supplier->name,
                'company' => $purchase->supplier->company,
                'phone' => $purchase->supplier->phone,
                'email' => $purchase->supplier->email,
            ],
            'products' => $products,
        ]);
    }


}
