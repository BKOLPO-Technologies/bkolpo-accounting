<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\JournalVoucher;
use App\Models\PurchaseProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pageTitle = 'Purchase';

        $purchases = PurchaseInvoice::OrderBy('id','desc')->get(); 
        //dd($purchases);
        return view('backend.admin.inventory.purchase.index',compact('pageTitle','purchases'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $suppliers = Supplier::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->where('group_name', 'purchases')->with('unit')->latest()->get();
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
        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;
        $units = Unit::where('status',1)->latest()->get();
        
        $purchases = Purchase::latest()->get();

        return view('backend.admin.inventory.purchase.create', compact(
            'pageTitle', 
            'suppliers', 
            'products',
            'categories',
            'projects',
            'purchases',
            'invoice_no',
            'vat',
            'tax',
            'units'
        )); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            // Validate the request
            $validated = $request->validate([
                'supplier' => 'required|exists:suppliers,id',
                'projects' => 'nullable|exists:projects,id',
                'purchase_id' => 'nullable|exists:purchases,id',
                'subtotal' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'vat_amount' => 'nullable|numeric|min:0',
                'total_netamount' => 'required|numeric|min:0',
                'grand_total' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'product_ids' => 'required|json',
                'quantities' => 'required|json',
                'prices' => 'required|json',
                'paid_amount' => 'nullable|numeric|min:0',
            ]);

            // Generate invoice number
            // $invoiceNumber = $this->generateInvoiceNumber();

            // Calculate payment status
            $status = 'pending';
            // if ($request->paid_amount > 0) {
            //     if ($request->paid_amount >= $validated['grand_total']) {
            //         $status = 'paid';
            //     } else {
            //         $status = 'partially_paid';
            //     }
            // }

            $originalPurchase = Purchase::find($validated['purchase_id']);

            // Create the purchase invoice
            $purchaseInvoice = PurchaseInvoice::create([
                'invoice_no' => $originalPurchase->invoice_no,
                'invoice_date' => now(),
                'supplier_id' => $validated['supplier'],
                'project_id' => $validated['projects'] ?? null,
                'purchase_id' => $validated['purchase_id'] ?? null,
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'] ?? 0,
                'tax_rate' => $validated['tax'] ?? 0,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'vat_rate' => $validated['vat'] ?? 0,
                'vat_amount' => $validated['vat_amount'] ?? 0,
                'total' => $validated['total_netamount'],
                'total_netamount' => $validated['total_netamount'],
                'grand_total' => $validated['grand_total'],
                'paid_amount' => $validated['paid_amount'] ?? 0,
                'status' => $status,
                'description' => $validated['description'],
            ]);

            // Process purchase items
            $productIds = json_decode($validated['product_ids'], true);
            $quantities = json_decode($validated['quantities'], true);
            $prices = json_decode($validated['prices'], true);

            $purchaseItems = [];
            foreach ($productIds as $index => $productId) {
                $quantity = $quantities[$index] ?? 0;
                $price = $prices[$index] ?? 0;
                $total = $quantity * $price;

                $purchaseItems[] = [
                    'purchase_invoice_id' => $purchaseInvoice->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Update product stock
                // Product::where('id', $productId)->increment('stock', $quantity);
            }

            // Bulk insert purchase items
            PurchaseInvoiceItem::insert($purchaseItems);

            // Handle payment if paid
            // if ($validated['paid_amount'] > 0) {
            //     $this->recordPayment($purchaseInvoice, $validated['paid_amount']);
            // }

            $purchase_amount = $purchaseInvoice->grand_total ?? 0;
            $purchasesLedger = Ledger::where('type', 'Purchases')->first();
            $payableLedger = Ledger::where('type', 'Payable')->first();

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
                // $new_invoice_no = 'BCL-INV-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;
                $new_invoice_no = $originalPurchase->invoice_no;

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
                    'reference_no' => $new_invoice_no ?? '',
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
                    'reference_no' => $new_invoice_no ?? '',
                    'description' => 'Supplier Payable Recorded for Purchase',
                    'debit' => 0,
                    'credit' => $purchase_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                //Log::info('Journal Voucher details created', ['journal_voucher_id' => $journalVoucher->id]);
            }

            DB::commit();

            return redirect()
                ->route('admin.purchase.invoice.index',)
                ->with('success', 'Purchase invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Purchase Invoice Creation Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create purchase invoice: ' . $e->getMessage());
        }
    }


    private function generateInvoiceNumber()
    {
        $prefix = 'PUR-';
        $datePart = date('Ymd');
        $latest = Purchase::where('invoice_no', 'like', $prefix.$datePart.'%')
            ->orderBy('invoice_no', 'desc')
            ->first();

        if (!$latest) {
            return $prefix.$datePart.'001';
        }

        $lastNumber = (int) substr($latest->invoice_no, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return $prefix.$datePart.$newNumber;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Purchase View';

        $purchase = PurchaseInvoice::where('id', $id)
            ->with(['products', 'supplier']) // Include supplier details
            ->first();
            
        $payments = Payment::where('invoice_no', $purchase->invoice_no)->get();
        return view('backend.admin.inventory.purchase.view',compact('pageTitle', 'purchase'));
    }

    public function AdminPurchaseInvoiceView2(Request $request)
    {
        $purchase = PurchaseInvoice::where('id', $request->id)
            ->with(['products', 'supplier'])
            ->first();

        $payments = Payment::where('invoice_no', $purchase->invoice_no)->get();

        return view('backend.admin.inventory.purchase.view_modal_part', compact('purchase', 'payments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(string $id)
    {
        $invoice = PurchaseInvoice::with([
                'supplier',
                'project',
                'items.product',
                'items.product.category',
                'items.product.unit'
            ])
            ->findOrFail($id);


            // dd($invoice);

        $suppliers = Supplier::all();
        $projects = Project::all();
        $products = Product::with(['category', 'unit'])->get();

        return view('backend.admin.inventory.purchase.edit', compact(
            'invoice',
            'suppliers',
            'projects',
            'products'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            // Decode JSON arrays first
            $requestData = $request->all();
            $requestData['product_ids'] = json_decode($request->product_ids, true) ?? [];
            $requestData['quantities'] = json_decode($request->quantities, true) ?? [];
            $requestData['prices'] = json_decode($request->prices, true) ?? [];

            $validated = Validator::make($requestData, [
                'supplier' => 'required|exists:suppliers,id',
                'projects' => 'nullable|exists:projects,id',
                'invoice_date' => 'required|date',
                'subtotal' => 'required|numeric|min:0',
                'total_discount' => 'nullable|numeric|min:0',
                'tax' => 'nullable|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'vat' => 'nullable|numeric|min:0',
                'vat_amount' => 'nullable|numeric|min:0',
                'grand_total' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'product_ids' => 'required|array',
                'product_ids.*' => 'required|integer|exists:products,id',
                'quantities' => 'required|array',
                'quantities.*' => 'required|numeric|min:0.01',
                'prices' => 'required|array',
                'prices.*' => 'required|numeric|min:0',
            ])->validate();

            $invoice = PurchaseInvoice::findOrFail($id);

            // Update invoice
            $invoice->update([
                'supplier_id' => $validated['supplier'],
                'project_id' => $validated['projects'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['total_discount'] ?? 0,
                'tax_rate' => $validated['tax'] ?? 0,
                'tax_amount' => $validated['tax_amount'] ?? 0,
                'vat_rate' => $validated['vat'] ?? 0,
                'vat_amount' => $validated['vat_amount'] ?? 0,
                'total' => $validated['subtotal'] - ($validated['total_discount'] ?? 0),
                'grand_total' => $validated['grand_total'],
                'description' => $validated['description'],
            ]);

            // Process items
            $currentItems = $invoice->items->keyBy('product_id');
            $newItems = [];

            foreach ($validated['product_ids'] as $index => $productId) {
                $quantity = $validated['quantities'][$index];
                $price = $validated['prices'][$index];
                $total = $quantity * $price;

                if ($currentItems->has($productId)) {
                    // Update existing item
                    $item = $currentItems->get($productId);
                    $stockDifference = $quantity - $item->quantity;

                    $item->update([
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                    ]);

                    // Update stock if changed
                    // if ($stockDifference != 0) {
                    //     Product::where('id', $productId)
                    //         ->increment('stock', $stockDifference);
                    // }
                } else {
                    // Create new item
                    $newItems[] = [
                        'purchase_invoice_id' => $invoice->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Update product stock
                    // Product::where('id', $productId)
                    //     ->increment('stock', $quantity);
                }
            }

            // Bulk insert new items
            if (!empty($newItems)) {
                PurchaseInvoiceItem::insert($newItems);
            }

            // Remove deleted items
            // $deletedItems = $currentItems->whereNotIn('product_id', $validated['product_ids']);
            // foreach ($deletedItems as $item) {
            //     // Revert stock for deleted items
            //     Product::where('id', $item->product_id)
            //         ->decrement('stock', $item->quantity);
            //     $item->delete();
            // }

            DB::commit();

            return redirect()
                ->route('admin.purchase.invoice.index')
                ->with('success', 'Invoice updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Invoice Update Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
 * Remove the specified purchase invoice from storage.
 *
 * @param  string  $id
 * @return \Illuminate\Http\RedirectResponse
 */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $invoice = PurchaseInvoice::with(['items', 'payments'])->findOrFail($id);

            // Check if invoice has payments (optional business rule)
            if ($invoice->payments->isNotEmpty()) {
                throw new \Exception('Cannot delete invoice with existing payments.');
            }

            // Option 1: If you need to revert stock (uncomment if needed)
            // foreach ($invoice->items as $item) {
            //     Product::where('id', $item->product_id)
            //         ->decrement('stock', $item->quantity);
            // }

            // Delete related items first
            $invoice->items()->delete();

            // Delete the invoice
            $invoice->delete();

            DB::commit();

            return redirect()
                ->route('admin.purchase.invoice.index')
                ->with('success', 'Invoice deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Invoice Deletion Error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Failed to delete invoice: ' . $e->getMessage());
        }
    }

    public function getPurchasesByProject($project_id)
    {
        $purchases = Purchase::where('project_id', $project_id)->get();

        return response()->json($purchases);
    }

    public function getProductsByPurchase($purchaseId)
    {
       $purchase = Purchase::with('purchaseProducts.product.unit', 'purchaseProducts.product.category')->find($purchaseId);

        return response()->json($purchase->purchaseProducts);
    }
}
