<?php

namespace App\Http\Controllers\Backend\Inventory;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Client;
use App\Models\Ledger;
use App\Models\Product;
use App\Models\Project;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\Log; 
use App\Models\OutcomingChalanProduct;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Invoice';

        $sales = Sale::with('products')->OrderBy('id','desc')->get(); 
        return view('backend.admin.inventory.sales.index',compact('pageTitle','sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('id', 'desc')->get();

        $products = Product::where('status',1)->latest()->get();
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->with(['items.product'])->latest()->get();
        //dd($projects);
        $pageTitle = 'Invoice';

        // Get current timestamp in 'dmyHis' format (day, month, year)
        // $randomNumber = rand(100000, 999999);
        // $fullDate = now()->format('d/m/y');

        // Combine the timestamp, random number, and full date
        // $invoice_no = 'BCL-INV-'.$fullDate.' - '.$randomNumber;

        $companyInfo = get_company(); 

        // Get the current date and month
        $currentMonth = now()->format('m'); // Current month (01-12)
        $currentYear = now()->format('y'); // Current year (yy)

        // Generate a random number for the current insert
        $randomNumber = rand(100000, 999999);

        // Get the last reference number for the current month
        $lastInvoiceNo = Sale::whereRaw('MONTH(created_at) = ?', [$currentMonth]) // Filter by the current month
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
        $invoice_no = 'BCL-INV-' . $fiscalYearWithoutHyphen . $currentMonth . $formattedIncrement;

        // Generate a random 8-digit number
        // $randomNumber = mt_rand(100000, 999999);

        // $invoice_no = 'BKOLPO-'. $randomNumber;

        $units = Unit::where('status',1)->latest()->get();

        $vat = $companyInfo->vat;
        $tax = $companyInfo->tax;
        
        return view('backend.admin.inventory.sales.create', compact(
            'pageTitle', 
            'clients', 
            'products',
            'categories',
            'projects',
            'invoice_no',
            'units',
            'vat',
            'tax'
        )); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate input
        $validated = $request->validate([
            'client'      => 'required|exists:clients,id',
            'invoice_no'  => 'required|unique:sales,invoice_no',
            'quantity'    => 'required|array|min:1',
            'unit_price'  => 'required|array',
            'order_unit'  => 'required|array',
        ]);
    
        try {
            DB::beginTransaction();
    
            $tax = $request->has('include_tax') ? ($request->tax ?? 0) : 0;
            $vat = $request->has('include_vat') ? ($request->vat ?? 0) : 0;
    
            // Save sale record
            $sale = Sale::create([
                'client_id'        => $validated['client'],
                'invoice_no'       => $validated['invoice_no'],
                'invoice_date'     => now()->format('Y-m-d'),
                'subtotal'         => $request->subtotal ?? 0,
                'discount'         => $request->total_discount ?? 0,
                'total_netamount'  => $request->total_netamount ?? 0,
                'transport_cost'   => $request->transport_cost ?? 0,
                'carrying_charge'  => $request->carrying_charge ?? 0,
                'vat'              => $vat,
                'vat_amount'       => $request->vat_amount ?? 0,
                'tax'              => $tax,
                'tax_amount'       => $request->tax_amount ?? 0,
                'total'            => $request->subtotal ?? 0,
                'grand_total'      => $request->grand_total ?? 0,
                'description'      => $request->description,
                'project_id'       => $request->projects, // you used "projects" instead of "project_id"
            ]);
    
            // Loop through products
            foreach ($request->order_unit as $index => $productId) {
                // dd($productId);
                $quantity = $request->quantity[$index] ?? 0;
                $unitPrice = $request->unit_price[$index] ?? 0;
                $discount = $request->discounts[$index] ?? 0; // fallback if provided
                $itemId    = $request->item_id[$index] ?? null; 
    
                SaleProduct::create([
                    'sale_id'    => $sale->id,
                    // 'product_id' => $productId,
                    'item_id' => $itemId,
                    'quantity'   => $quantity,
                    'price'      => $unitPrice,
                    'discount'   => $discount ?? 0,
                    'subtotal'   => $quantity * $unitPrice,
                    'total'      => ($quantity * $unitPrice) - ($discount ?? 0),
                ]);
            }
    
            // Ledger & Journal Voucher logic
            $saleAmount = $sale->grand_total ?? 0;
            $salesLedger = Ledger::where('type', 'Sales')->first();
            $receivableLedger = Ledger::where('type', 'Receivable')->first();
            $vatLedger = Ledger::where('type', 'Vat')->first();
            $taxLedger = Ledger::where('type', 'Tax')->first();

            $vatAmount = $sale->vat_amount ?? 0;
            $taxAmount = $sale->tax_amount ?? 0;

            $netSalesAmount = $saleAmount + ($vatAmount + $taxAmount);

            if ($salesLedger && $receivableLedger) {
                $companyInfo = get_company(); 
                $currentMonth = now()->format('m');
                $fiscalYear = str_replace('-', '', $companyInfo->fiscal_year);

                $latestJV = JournalVoucher::whereRaw('MONTH(created_at) = ?', [$currentMonth])
                            ->orderBy('created_at', 'desc')
                            ->first();

                $increment = $latestJV && preg_match('/(\d{3})$/', $latestJV->transaction_code, $matches)
                            ? ((int)$matches[0] + 1)
                            : 1;

                $transactionCode = 'BCL-V-' . $fiscalYear . $currentMonth . str_pad($increment, 3, '0', STR_PAD_LEFT);

                $journalVoucher = JournalVoucher::create([
                    'transaction_code' => $transactionCode,
                    'company_id'       => $companyInfo->id,
                    'branch_id'        => $companyInfo->branch->id,
                    'transaction_date' => now()->format('Y-m-d'),
                    'description'      => 'Invoice Entry for Sales',
                    'status'           => 1,
                ]);

              $details = [
                    // Receivable Debit
                    [
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id'          => $receivableLedger->id,
                        'reference_no'       => $sale->invoice_no,
                        'description'        => 'Receivable for Invoice ' . $sale->invoice_no,
                        'debit'              => $saleAmount,
                        'credit'             => 0,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ],
                ];

                // VAT Debit (insert before Sales)
                if ($vatLedger && $vatAmount > 0) {
                    $details[] = [
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id'          => $vatLedger->id,
                        'reference_no'       => $sale->invoice_no,
                        'description'        => 'VAT for Invoice ' . $sale->invoice_no,
                        'debit'              => $vatAmount,
                        'credit'             => 0,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];
                }

                // Tax Debit (insert before Sales)
                if ($taxLedger && $taxAmount > 0) {
                    $details[] = [
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id'          => $taxLedger->id,
                        'reference_no'       => $sale->invoice_no,
                        'description'        => 'Tax for Invoice ' . $sale->invoice_no,
                        'debit'              => $taxAmount,
                        'credit'             => 0,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];
                }

                // Sales Credit (last)
                $details[] = [
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id'          => $salesLedger->id,
                    'reference_no'       => $sale->invoice_no,
                    'description'        => 'Sales for Invoice ' . $sale->invoice_no,
                    'debit'              => 0,
                    'credit'             => $netSalesAmount,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];

                JournalVoucherDetail::insert($details);
            }
    
            DB::commit();
            return redirect()->route('admin.sale.index')->with('success', 'Sale created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Sale creation failed:', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function view($id)
    {
        $pageTitle = 'Invoice View';

        $sale = Sale::where('id', $id)
            ->with(['saleProducts.item.unit', 'client']) // Include supplier details
            ->first();

        return view('backend.admin.inventory.sales.view',compact('pageTitle', 'sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pageTitle = 'Invoice Edit';

        $products = Product::where('status',1)->latest()->get();

        // Fetch purchase details with supplier and products
        $sale = Sale::where('id', $id)
            ->with(['products', 'client']) // Include supplier details
            ->first();

        // dd($sale->saleProducts);

        // $h = $sale->saleProducts;

        // Fetch purchase details with supplier and products
        $purchase = Purchase::where('id', $id)
            ->with(['products', 'supplier', 'project']) // Include supplier details
            ->first();

        //dd($purchase);
        
        // if ($purchase->invoice_date) {
        //     $purchase->invoice_date = Carbon::parse($purchase->invoice_date);
        // }

        if ($sale->invoice_date) {
            $sale->invoice_date = Carbon::parse($sale->invoice_date);
        }

        // $subtotal = $purchase->products->sum(function ($product) {
        //     return $product->pivot->price * $product->pivot->quantity - $product->pivot->discount;
        // });

        $subtotal = $sale->products->sum(function ($product) {
            return ($product->pivot->price * $product->pivot->quantity) - (!empty($product->pivot->discount) ? $product->pivot->discount : 0);
        });

        //$grandtotal = $subtotal + (($purchase->transport_cost) + ($purchase->carrying_charge) + ($purchase->vat) + ($purchase->tax) - ($purchase->discount));
        $grandtotal = $subtotal + (($sale->transport_cost) + ($sale->carrying_charge) + ($sale->vat_amount) + ($sale->tax_amount) - ($sale->discount));

        $suppliers = Supplier::orderBy('id', 'desc')->get();
        $clients = Client::orderBy('id', 'desc')->get();
        $aproducts = Product::where('status',1)->latest()->get();
        $categories = Category::where('status',1)->latest()->get();
        $projects = Project::where('project_type','Running')->latest()->get();

        //$product_ids = $purchase->products->pluck('id')->implode(',');
        $product_ids = $sale->products->pluck('id')->implode(',');
        //$quantities = $purchase->products->pluck('pivot.quantity')->implode(',');
        $quantities = $sale->products->pluck('pivot.quantity')->implode(',');
        //$prices = $purchase->products->pluck('pivot.price')->implode(',');
        $prices = $sale->products->pluck('pivot.price')->implode(',');
        //$discounts = $purchase->products->pluck('pivot.discount')->implode(',');
        $discounts = $sale->products->pluck('pivot.discount')->implode(',');

        //return view('backend.admin.inventory.sales.edit',compact('pageTitle', 'sale', 'clients', 'products', 'subtotal', 'projects'));

        $units = Unit::where('status',1)->latest()->get();

        return view('backend.admin.inventory.sales.edit', [
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
            'sale' => $sale,
            'clients' => $clients,
            'products' => $products,
            'units' => $units,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        //dd($request->all());
        // Step 1: Validate
        $validated = $request->validate([
            'client'      => 'required|exists:clients,id',
            'invoice_no'  => 'required|unique:sales,invoice_no,' . $id,
            'order_unit'  => 'required|array|min:1',
            'quantity'    => 'required|array|min:1',
            'unit_price'  => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Step 2: Find sale
            $sale = Sale::findOrFail($id);

            // Step 3: Update sale fields
            $tax = $request->has('include_tax') ? ($request->tax ?? 0) : 0;
            $vat = $request->has('include_vat') ? ($request->vat ?? 0) : 0;

            $sale->update([
                'client_id'        => $validated['client'],
                'invoice_no'       => $validated['invoice_no'],
                'invoice_date'     => now()->format('Y-m-d'),
                'subtotal'         => $request->subtotal ?? 0,
                'discount'         => $request->total_discount ?? 0,
                'total_netamount'  => $request->total_netamount ?? 0,
                'transport_cost'   => $request->transport_cost ?? 0,
                'carrying_charge'  => $request->carrying_charge ?? 0,
                'vat'              => $vat,
                'vat_amount'       => $request->vat_amount ?? 0,
                'tax'              => $tax,
                'tax_amount'       => $request->tax_amount ?? 0,
                'total'            => $request->subtotal ?? 0,
                'grand_total'      => $request->grand_total ?? 0,
                'description'      => $request->description,
                //'project_id'       => $request->projects ?? null,
                'project_id'       => $request->project_id ?? null,
            ]);

            // Step 4: Delete old sale products
            $sale->saleProducts()->delete();

            // Step 5: Add updated sale products
            foreach ($request->order_unit as $index => $productId) {
                $quantity  = $request->quantity[$index] ?? 0;
                $unitPrice = $request->unit_price[$index] ?? 0;
                //$discount  = $request->discounts[$index] ?? 0;
                $discount = (float) ($request->discount[$index] ?? 0);
                $itemId = $request->item_id[$index] ?? null;

                SaleProduct::create([
                    'sale_id'    => $sale->id,
                    // 'product_id' => $productId,
                    'item_id'    => $itemId,
                    'quantity'   => $quantity,
                    'price'      => $unitPrice,
                    'discount'   => $discount,
                    'subtotal'   => $quantity * $unitPrice,
                    'total' => ((float)$quantity * (float)$unitPrice) - (float)$discount,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.sale.index')->with('success', 'Sale updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Sale update failed:', [
                'error'    => $e->getMessage(),
                'user_id'  => auth()->id(),
                'sale_id'  => $id,
            ]);

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::find($id);
    
        if ($sale) {

            // Delete related payments using the defined relationship
            $sale->receipts()->delete();

            // Detach the related SaleProduct records (pivot table entries)
            $sale->products()->detach();
    
            // Delete the sale itself
            $sale->delete();
    
            return redirect()->back()->with('success', 'Sale and related products deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Sale and related products deleted successfully..');
    }

    public function getInvoiceDetails($id)
    {
        //Log::info("Fetching sale invoice details for ID: {$id}");

        $sale = Sale::with(['client', 'saleProducts.product'])->find($id);

        if (!$sale) {
            //Log::error("Invoice not found for ID: {$id}");
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // // Way-1
        // // Fetch products along with receive_quantity from OutcomingChalanProduct
        $products = $sale->saleProducts->map(function ($saleProduct) use ($id) {
            // Find the receive quantity from the outcoming_chalan_product table
            $receiveQuantity = OutcomingChalanProduct::where('product_id', $saleProduct->product->id)
                ->whereHas('outcomingChalan', function ($query) use ($id) {
                    $query->where('sale_id', $id);
                })
                ->sum('receive_quantity'); // Sum in case of multiple entries

        // // Way-2
        // // Fetch products and receive_quantity via relationship
        // $products = $sale->saleProducts->map(function ($saleProduct) {
        //     $receiveQuantity = $saleProduct->product->receivedQuantities->sum('receive_quantity');

            // Log::debug("Processing product:", [
            //     'id' => $saleProduct->product->id,
            //     'name' => $saleProduct->product->name,
            //     'price' => $saleProduct->price,
            //     'quantity' => $saleProduct->quantity,
            //     'discount' => $saleProduct->discount,
            //     'stockqty' => $saleProduct->product->quantity,
            //     'receive_quantity' => $receiveQuantity,
            // ]);

            return [
                'id' => $saleProduct->product->id,
                'name' => $saleProduct->product->name,
                'price' => $saleProduct->price,
                'quantity' => $saleProduct->quantity,
                'discount' => $saleProduct->discount,
                'stockqty' => $saleProduct->product->quantity,
                'receive_quantity' => $receiveQuantity, // Now sending correct receive_quantity
            ];
        });

        //Log::info("Successfully retrieved invoice details for ID: {$id}");

        return response()->json([
            'client' => [
                'name' => $sale->client->name,
                'company' => $sale->client->company,
                'phone' => $sale->client->phone,
                'email' => $sale->client->email,
            ],
            'products' => $products,
        ]);
    }
    
}
