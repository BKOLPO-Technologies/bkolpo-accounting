<?php

namespace App\Http\Controllers\Backend;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function AdminSupplierIndex() 
    {
        $suppliers = Supplier::all();
        $pageTitle = 'Admin Supplier';
        return view('backend/admin/supplier/index',compact('pageTitle', 'suppliers'));
    }

    public function AdminSupplierCreate() {

        $pageTitle = 'Admin Supplier Create';
        return view('backend/admin/supplier/create',compact('pageTitle'));

    }

    public function AdminSupplierView($id)
    {
        //dd($id);
        $supplier = Supplier::findOrFail($id);
        //dd($supplier);
        $pageTitle = 'Admin Supplier View';
        return view('backend/admin/supplier/view',compact('pageTitle', 'supplier'));

    }

    public function AdminSupplierEdit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $pageTitle = 'Admin Supplier Edit';
        return view('backend/admin/supplier/edit',compact('pageTitle', 'supplier'));
    }

    public function AdminSupplierStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:11',
            'email'    => 'nullable|email|unique:suppliers,email',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'region'   => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'postbox'  => 'nullable|string|max:20',
            'taxid'    => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'active'   => 'boolean',
        ]);

        $supplier = Supplier::create([
            'name'     => $request->name,
            'company'  => $request->company,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'city'     => $request->city,
            'region'   => $request->region,
            'country'  => $request->country,
            'postbox'  => $request->postbox,
            'taxid'    => $request->taxid,
            'password' => $request->password ? Hash::make($request->password) : null,
            'active'   => $request->active ?? true,
        ]);

        return redirect()->route('admin.supplier.index')->with('success', 'Supplier added successfully.');
    }

    public function AdminSupplierStore2(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:11',
            'email'    => 'nullable|email|unique:suppliers,email',
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'region'   => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'postbox'  => 'nullable|string|max:20',
            'taxid'    => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'active'   => 'boolean',
        ]);

        $supplier = Supplier::create([
            'name'     => $request->name,
            'company'  => $request->company,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'address'  => $request->address,
            'city'     => $request->city,
            'region'   => $request->region,
            'country'  => $request->country,
            'postbox'  => $request->postbox,
            'taxid'    => $request->taxid,
            'password' => $request->password ? Hash::make($request->password) : null,
            'active'   => $request->active ?? true,
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
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $id, // Ensure unique email excluding current supplier
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postbox' => 'nullable|string|max:255',
            'taxid' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6', // Only validate password if provided
            'active' => 'nullable|boolean'
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $supplier = Supplier::findOrFail($id);

        // Update the supplier data
        $supplier->update([
            'name' => $request->input('name'),
            'company' => $request->input('company'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'region' => $request->input('region'),
            'country' => $request->input('country'),
            'postbox' => $request->input('postbox'),
            'taxid' => $request->input('taxid'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $supplier->password, // Only update password if provided
            'active' => $request->input('active') ?? $supplier->active, // Set default active value if not provided
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


}
