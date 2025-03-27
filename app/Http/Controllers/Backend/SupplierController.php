<?php

namespace App\Http\Controllers\Backend;

use App\Models\Supplier;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function AdminSupplierIndex() 
    {
        $suppliers = Supplier::all();
        $pageTitle = 'Vendor List';
        return view('backend.admin.supplier.index',compact('pageTitle', 'suppliers'));
    }

    public function AdminSupplierCreate() {

        $pageTitle = 'Vendor Create';
        return view('backend.admin.supplier.create',compact('pageTitle'));

    }

    public function AdminSupplierView($id)
    {
        $supplier = Supplier::findOrFail($id);
        $pageTitle = 'Vendor View';

        $totalPurchaseAmount = $supplier->totalPurchaseAmount();
        $totalPaidAmount = $supplier->totalPaidAmount();
        $totalDueAmount = $supplier->totalDueAmount();

        return view('backend.admin.supplier.view',compact('pageTitle', 'supplier','totalPurchaseAmount','totalPaidAmount','totalDueAmount'));

    }

    public function AdminSupplierEdit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $pageTitle = 'Vendor Edit';
        return view('backend.admin.supplier.edit',compact('pageTitle', 'supplier'));
    }

    public function AdminSupplierStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:11',
            'email' => 'nullable|email|unique:suppliers,email',
            'address' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postbox' => 'nullable|string|max:20',
            'taxid' => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'title' => $request->title,
            'company' => $request->company,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'zip' => $request->zip,
            'city' => $request->city,
            'region' => $request->region,
            'country' => $request->country,
            'postbox' => $request->postbox,
            'taxid' => $request->taxid,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            // 'status'   => $request->status ?? 1,
        ]);

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier added successfully.');
    }

    public function AdminSupplierStore2(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            // 'designation' => 'required|string|max:255',
            // 'title' => 'required|string|max:255',
            // 'company'  => 'nullable|string|max:255',
            // 'phone'    => 'nullable|string|max:11',
            // 'email'    => 'nullable|email|unique:suppliers,email',
            // 'address'  => 'nullable|string',
            // 'zip' => 'nullable|string',
            // 'city'     => 'nullable|string|max:100',
            // 'region'   => 'nullable|string|max:100',
            // 'country'  => 'nullable|string|max:100',
            // 'postbox'  => 'nullable|string|max:20',
            // 'taxid'    => 'nullable|string|max:50',
            // 'bin' => 'nullable|string|max:50',
            // 'password' => 'nullable|string|min:6',
        ]);

        $supplier = Supplier::create([
            'name'     => $request->name,
            'designation' => $request->designation,
            'title' => $request->title,
            'company'  => $request->company,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'zip' => $request->zip,
            'city'     => $request->city,
            'region'   => $request->region,
            'country'  => $request->country,
            'postbox'  => $request->postbox,
            'taxid'    => $request->taxid,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            // 'status'   => $request->status ?? 1,
        ]);

        //return redirect()->route('admin.supplier.index')->with('success', 'Supplier added successfully.');
        return response()->json([
            'success'  => true,
            'message'  => 'Supplier added successfully.',
            'supplier' => $supplier, // Send back the created supplier data
        ]);
        
    }

    public function AdminSupplierUpdate(Request $request, $id)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $id, // Ensure unique email excluding current supplier
            'address' => 'nullable|string|max:500',
            'zip' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postbox' => 'nullable|string|max:255',
            'taxid' => 'nullable|string|max:20',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6', // Only validate password if provided
            'active' => 'nullable|boolean'
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $supplier = Supplier::findOrFail($id);

        // Update the supplier data
        $supplier->update([
            'name' => $request->input('name'),
            'designation' => $request->input('designation'),
            'title' => $request->input('title'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'zip' => $request->input('zip'),
            'city' => $request->input('city'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'postbox' => $request->input('postbox'),
            'taxid' => $request->input('taxid'),
            'bin' => $request->input('bin'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $supplier->password, // Only update password if provided
            // 'active' => $request->input('active') ?? $supplier->active, // Set default active value if not provided
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier updated successfully!');
    }

    public function AdminSupplierDestroy($id)
    {
        // Find the supplier by ID
        $supplier = Supplier::findOrFail($id);

        // Delete the supplier record
        $supplier->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.supplier.index')->with('success', 'Supplier deleted successfully!');
    }


    public function viewProducts($supplierId)
    {
        // Fetch the supplier
        $supplier = Supplier::findOrFail($supplierId);

        // Fetch the purchased products for this supplier
        $purchasedProducts = Purchase::where('supplier_id', $supplierId)
                                    ->with('products') // Assuming Purchase has a relation with products
                                    ->get();

        $pageTitle = 'Purchased Products History';

        $totalPurchaseAmount = $supplier->totalPurchaseAmount();
        $totalPaidAmount = $supplier->totalPaidAmount();
        $totalDueAmount = $supplier->totalDueAmount();
        $totalDiscounta = $supplier->totalDiscount();

        // Return the view for purchased products
        return view('backend.admin.supplier.products', compact('pageTitle','supplier', 'purchasedProducts','totalPurchaseAmount','totalDiscounta','totalPaidAmount','totalDueAmount'));
    }

    public function viewTransactions($supplierId)
    {
        // Fetch the supplier
        $supplier = Supplier::findOrFail($supplierId);

        // Fetch the transactions/payments related to this supplier
        $transactions = Payment::where('supplier_id', $supplierId)->get();

        $pageTitle = 'Supplier Transactions';

        // Return the view for transactions
        return view('backend.admin.supplier.transactions', compact('pageTitle','supplier', 'transactions'));
    }



}
