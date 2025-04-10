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
        $pageTitle = 'Purchase Order';

        $purchases = Purchase::with('products')->OrderBy('id','desc')->get(); 
        //dd($purchases);
        return view('backend.admin.inventory.purchase.index',compact('pageTitle','purchases'));

    }

    public function AdminPurchaseCreate()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->with('unit')->latest()->get();
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->latest()->get();
        $pageTitle = 'Purchase';

        // // Get current timestamp in 'dmyHis' format (day, month, year)
        // $randomNumber = rand(100000, 999999);
        // $fullDate = now()->format('d/m/y');

        // // Combine the timestamp, random number, and full date
        // $invoice_no = 'BCL-PO-'.$fullDate.' - '.$randomNumber;

        $companyInfo = get_company(); 

        // Get the current date and month
        $currentMonth = now()->format('m'); // Current month (01-12)
        $currentYear = now()->format('y'); // Current year (yy)

        // Generate a random number for the current insert
        $randomNumber = rand(100000, 999999);

        // Get the last reference number for the current month
        $lastInvoiceNo = Purchase::whereRaw('MONTH(created_at) = ?', [$currentMonth]) // Filter by the current month
        ->orderBy('created_at', 'desc') // Order by the latest created entry
        ->first(); // Get the latest entry

        // Increment the last Invoice No number for this month
        if ($lastInvoiceNo) {
            // Extract the incremental part from the last reference number
            preg_match('/(\d{3})$/', $lastInvoiceNo->invoice_no, $matches); // Assuming the last part is always 3 digits (001, 002, etc.)
            $increment = (int)$matches[0] + 1; // Increment the number
        } else {
            // If no reference exists for the current month, start from 001
            $increment = 1;
        }

        // Format the increment to be always 3 digits (e.g., 001, 002, 003)
        $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);


        // Remove the hyphen from fiscal year (e.g., "24-25" becomes "2425")
        $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);

        // Combine fiscal year, current month, and the incremental number to generate the reference number
        $invoice_no = 'BCL-PO-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;


        // // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $invoice_no = 'BKOLPO-'. $randomNumber;

        return view('backend.admin.inventory.purchase.create', compact(
            'pageTitle', 
            'suppliers', 
            'products',
            'categories',
            'projects',
            'invoice_no'
        )); 
    }

    // purchase store

    public function AdminPurchaseStore(Request $request)
    {
        //dd($request->all());
        //Log::info('AdminPurchaseStore function started', ['request_data' => $request->all()]);

        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no',
            // 'subtotal' => 'required|numeric',
            // 'discount' => 'required|numeric',
            // 'total' => 'required|numeric',
            'product_ids' => 'required|not_in:',  // Ensure at least one product is selected
            'project_id' => 'required|exists:projects,id',
        ]);

        //Log::info('Request validated successfully', ['validated_data' => $validated]);

        // Access product data from the request
        $productIds = explode(',', $request->input('product_ids')); 
        $quantities = explode(',', $request->input('quantities')); 
        $prices = explode(',', $request->input('prices')); 
        $discounts = explode(',', $request->input('discounts')); 

        // Log::info('Extracted product data', [
        //     'productIds' => $productIds,
        //     'quantities' => $quantities,
        //     'prices' => $prices,
        //     'discounts' => $discounts
        // ]);

        // Check if at least one product is selected
        if (empty($productIds) || count($productIds) === 0 || $productIds[0] == '') {
            //Log::error('No product selected');
            return back()->with('error', 'At least one product must be selected.');
        }

        try {
            DB::beginTransaction();
            //Log::info('Transaction started');

            $categoryId = $request->category_id == 'all' ? null : $request->category_id;
            //Log::info('Category ID determined', ['categoryId' => $categoryId]);


            $tax = $request->include_tax ? $request->tax : 0; 
            $vat = $request->include_vat ? $request->vat : 0; 

            // Create a new purchase record
            $purchase = new Purchase();
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = now()->format('Y-m-d');
            $purchase->subtotal = $request->subtotal;
            $purchase->discount = $request->total_discount;
            $purchase->total_netamount = $request->total_netamount ?? 0;
            $purchase->transport_cost = $request->transport_cost;
            $purchase->carrying_charge = $request->carrying_charge;
            $purchase->vat = $vat;
            $purchase->vat_amount = $request->vat_amount;
            $purchase->tax = $tax;
            $purchase->tax_amount = $request->tax_amount;
            $purchase->total = $request->subtotal;
            $purchase->grand_total = $request->grand_total;
            $purchase->description = $request->description;
            $purchase->category_id = '1';
            $purchase->project_id = $request->project_id;
            $purchase->save();

            //Log::info('Purchase record created', ['purchase_id' => $purchase->id]);

            // Loop through the product data and save it to the database
            foreach ($productIds as $index => $productId) {
                $product = Product::find($productId);
                if (!$product) {
                    //Log::error("Product not found", ['productId' => $productId]);
                    continue;
                }

                $quantity = $quantities[$index];
                $price = $prices[$index];
                $discount = $discounts[$index] ?? 0;

                // Insert into purchase_product table
                $purchaseProduct = new PurchaseProduct();
                $purchaseProduct->purchase_id = $purchase->id;
                $purchaseProduct->product_id = $productId;
                $purchaseProduct->quantity = $quantity;
                $purchaseProduct->price = $price;
                $purchaseProduct->discount = $discount ?: 0;  // If discount is empty, set it to 0
                $purchaseProduct->save();

                // Log::info('Product added to purchase', [
                //     'purchase_id' => $purchase->id,
                //     'product_id' => $productId,
                //     'quantity' => $quantity,
                //     'price' => $price,
                //     'discount' => $discount
                // ]);
            }

            $purchase_amount = $purchase->grand_total ?? 0;
            //Log::info('Purchase amount calculated', ['purchase_amount' => $purchase_amount]);

            // $purchasesLedger = Ledger::where('name', 'Purchases')->first();
            // $payableLedger = Ledger::where('name', 'Accounts Payable')->first();

            $purchasesLedger = Ledger::where('type', 'Purchases')->first();
            $payableLedger = Ledger::where('type', 'Payable')->first();

            // // Get current timestamp in 'dmyHis' format (day, month, year)
            // $randomNumber = rand(100000, 999999);
            // $fullDate = now()->format('d/m/y');

            // // Combine the timestamp, random number, and full date
            // $transactionCode = 'BCL-V-'.$fullDate.' - '.$randomNumber;

            if ($purchasesLedger && $payableLedger) {

                // 09-04-2025 new code //
                $companyInfo = get_company(); 
                $currentMonth = now()->format('m');
                $currentYear = now()->format('y');
                $randomNumber = rand(100000, 999999);

                $journalVoucher = JournalVoucher::whereRaw('MONTH(created_at) = ?', [$currentMonth]) 
                ->orderBy('created_at', 'desc') 
                // ->where('transaction_code', $sale->invoice_no)
                ->first(); 

                if ($journalVoucher) {
                    preg_match('/(\d{3})$/', $journalVoucher->transaction_code, $matches); 
                    $increment = (int)$matches[0] + 1;
                }else{
                    $increment = 1;
                }

                $formattedIncrement = str_pad($increment, 3, '0', STR_PAD_LEFT);
                $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);

                $transactionCode = 'BCL-V-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;

                $journalVoucher = JournalVoucher::create([
                    'transaction_code' => $transactionCode,
                    'transaction_date' => now()->format('Y-m-d'),
                    'description' => 'Purchase PO No Recorded - Supplier',
                    'status' => 1, 
                ]);

                // Purchase -> Purchases Account (Debit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $purchasesLedger->id,
                    'reference_no' => $purchase->invoice_no ?? '',
                    'description' => 'Purchased Goods from Supplier',
                    'debit' => $purchase_amount,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Purchase Payable -> Accounts Payable (Credit Entry)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $payableLedger->id,
                    'reference_no' => $purchase->invoice_no ?? '',
                    'description' => 'Supplier Payable Recorded for Purchase',
                    'debit' => 0,
                    'credit' => $purchase_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                //Log::info('Journal Voucher details created', ['journal_voucher_id' => $journalVoucher->id]);
            }

            DB::commit();
            //Log::info('Transaction committed successfully');

            return redirect()->route('admin.purchase.index')->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            //Log::error('Transaction failed', ['error' => $e->getMessage()]);
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

    public function AdminPurchaseView2(Request $request)
    {
        $purchase = Purchase::where('id', $request->id)
            ->with(['products', 'supplier'])
            ->first();

        $payments = Payment::where('invoice_no', $purchase->invoice_no)->get();

        return view('backend.admin.inventory.purchase.view_modal_part', compact('purchase', 'payments'));
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
            ->with(['products', 'supplier', 'project']) // Include supplier details
            ->first();

        //dd($purchase);
        
        if ($purchase->invoice_date) {
            $purchase->invoice_date = Carbon::parse($purchase->invoice_date);
        }

        $subtotal = $purchase->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity - $product->pivot->discount;
        });

        $grandtotal = $subtotal + (($purchase->transport_cost) + ($purchase->carrying_charge) + ($purchase->vat) + ($purchase->tax) - ($purchase->discount));

        $suppliers = Supplier::orderBy('id', 'desc')->get();
        $aproducts = Product::where('status',1)->latest()->get();
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->latest()->get();

        $product_ids = $purchase->products->pluck('id')->implode(',');
        $quantities = $purchase->products->pluck('pivot.quantity')->implode(',');
        $prices = $purchase->products->pluck('pivot.price')->implode(',');
        $discounts = $purchase->products->pluck('pivot.discount')->implode(',');

        return view('backend.admin.inventory.purchase.edit', [
            'pageTitle' => $pageTitle, 
            'purchase' => $purchase, 
            'suppliers' => $suppliers, 
            'aproducts' => $aproducts,
            'categories' => $categories,
            'projects' => $projects, 
            'subtotal' => $subtotal, 
            'grandtotal' => $grandtotal,
            'grandtotal' => $grandtotal,
            'product_ids' => $product_ids,
            'quantities' => $quantities,
            'prices' => $prices,
            'discounts' => $discounts,
        ]);
    }

    public function AdminPurchaseUpdate(Request $request, $id)
    {
        //dd($request->all());
        // Validate the request data
        $validated = $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no,' . $id, // Allow current invoice_no
            // 'invoice_date' => 'required|date',
            // 'subtotal' => 'required|numeric',
            // 'discount' => 'required|numeric',
            // 'total' => 'required|numeric',
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
            DB::beginTransaction();

            if($request->category_id == 'all'){
                $categoryId = null;
            }else{
                $categoryId = $request->category_id;
            }

            $tax = $request->include_tax ? $request->tax : 0; 
            $vat = $request->include_vat ? $request->vat : 0; 

            // Find the existing purchase record
            $purchase = Purchase::findOrFail($id);
            $purchase->supplier_id = $validated['supplier'];
            $purchase->invoice_no = $validated['invoice_no'];
            $purchase->invoice_date = now()->format('Y-m-d');
            $purchase->subtotal = $request->subtotal;
            $purchase->discount = $request->total_discount;
            $purchase->total_netamount = $request->total_netamount ?? 0;
            $purchase->transport_cost = $request->transport_cost;
            $purchase->carrying_charge = $request->carrying_charge;
            $purchase->vat = $vat;
            $purchase->vat_amount = $request->vat_amount;
            $purchase->tax = $tax;
            $purchase->tax_amount = $request->tax_amount;
            $purchase->total = $request->subtotal;
            $purchase->grand_total = $request->grand_total;
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

            DB::commit();

            return redirect()->route('admin.purchase.index')->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
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
