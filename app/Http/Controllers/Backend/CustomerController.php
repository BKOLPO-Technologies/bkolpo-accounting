<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function AdminCustomerIndex()
    {
        $customers = Customer::all();
        $pageTitle = 'Admin Customer';
        return view('backend/admin/customer/index', compact('pageTitle', 'customers'));
    }

    public function AdminCustomerCreate()
    {
        $pageTitle = 'Admin Customer Create';
        return view('backend/admin/customer/create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
        ]);

        // Save customer data to the database
        $customer = new Customer();
        
        // Billing Address
        $customer->name = $request->name;
        $customer->company = $request->company;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->region = $request->region;
        $customer->country = $request->country;
        $customer->postbox = $request->postbox;
        $customer->taxid = $request->taxid;
        $customer->customergroup = $request->customergroup;

        // Shipping Address
        if ($request->has('customer1')) { // Same as Billing
            $customer->name_s = $request->name;
            $customer->phone_s = $request->phone;
            $customer->email_s = $request->email;
            $customer->address_s = $request->address;
            $customer->city_s = $request->city;
            $customer->region_s = $request->region;
            $customer->country_s = $request->country;
            $customer->postbox_s = $request->postbox;
        } else { // Custom Shipping Address
            $customer->name_s = $request->name_s;
            $customer->phone_s = $request->phone_s;
            $customer->email_s = $request->email_s;
            $customer->address_s = $request->address_s;
            $customer->city_s = $request->city_s;
            $customer->region_s = $request->region_s;
            $customer->country_s = $request->country_s;
            $customer->postbox_s = $request->postbox_s;
        }

        $customer->save();

        // Redirect or return response
        return redirect()->back()->with('success', 'Customer added successfully!');
    }

    public function edit($id)
    {
        // Fetch the customer to be edited
        $customer = Customer::findOrFail($id);

        // Pass the customer data to the edit view
        return view('backend/admin/customer/edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
        ]);

        // Fetch the customer record
        $customer = Customer::findOrFail($id);

        // Update billing address
        $customer->name = $request->name;
        $customer->company = $request->company;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->city = $request->city;
        $customer->region = $request->region;
        $customer->country = $request->country;
        $customer->postbox = $request->postbox;
        $customer->taxid = $request->taxid;
        $customer->customergroup = $request->customergroup;

        // Handle "Same As Billing"
        if ($request->has('same_as_billing')) {
            $customer->name_s = $request->name;
            $customer->phone_s = $request->phone;
            $customer->email_s = $request->email;
            $customer->address_s = $request->address;
            $customer->city_s = $request->city;
            $customer->region_s = $request->region;
            $customer->country_s = $request->country;
            $customer->postbox_s = $request->postbox;
        } else {
            // Update shipping address with provided data
            $customer->name_s = $request->name_s;
            $customer->phone_s = $request->phone_s;
            $customer->email_s = $request->email_s;
            $customer->address_s = $request->address_s;
            $customer->city_s = $request->city_s;
            $customer->region_s = $request->region_s;
            $customer->country_s = $request->country_s;
            $customer->postbox_s = $request->postbox_s;
        }

        // Save the changes
        $customer->save();

        // Redirect with success message
        return redirect()->route('admin.customer.index')->with('success', 'Customer updated successfully!');
    }


}
