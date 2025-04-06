<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Sale;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function AdminClientIndex()
    {
        $clients = Client::all();

        $pageTitle = 'Client List';
        return view('backend.admin.inventory.client.index',compact('pageTitle', 'clients'));
    }

    public function AdminClientCreate()
    {
        $pageTitle = 'Client Create';
        return view('backend.admin.inventory.client.create',compact('pageTitle'));
    }

    public function AdminClientView($id)
    {
        $client = Client::findOrFail($id);
        $pageTitle = 'Client View';

        $totalPurchaseAmount = $client->totalPurchaseAmount();
        $totalPaidAmount = $client->totalPaidAmount();
        $totalDueAmount = $client->totalDueAmount();

        return view('backend.admin.inventory.client.view',compact('pageTitle', 'client','totalPurchaseAmount','totalPaidAmount','totalDueAmount'));

    }

    public function AdminClientEdit($id)
    {
        $client = Client::findOrFail($id);
        $pageTitle = 'Client Edit';
        return view('backend.admin.inventory.client.edit',compact('pageTitle', 'client'));
    }

    public function AdminClientStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'company'  => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:11',
            'email'    => 'nullable|email|unique:suppliers,email',
            'address'  => 'nullable|string',
            'zip' => 'nullable|string',
            'city'     => 'nullable|string|max:100',
            'region'   => 'nullable|string|max:100',
            'country'  => 'nullable|string|max:100',
            'postbox'  => 'nullable|string|max:20',
            'taxid'    => 'nullable|string|max:50',
            'bin' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'status'   => 'boolean',
        ]);

        $client = Client::create([
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
            'bank_account_name'    => $request->bank_account_name,
            'bank_account_number'    => $request->bank_account_number,
            'bank_routing_number'    => $request->bank_routing_number,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            'status'   => $request->status ?? true,
        ]);

        return redirect()->route('admin.client.index')->with('success', 'Client added successfully.');
    }

    public function AdminClientStore2(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name'     => 'nullable|string|max:255',
            // 'designation' => 'nullable|string|max:255',
            // 'title' => 'required|string|max:255',
            // 'company'  => 'nullable|string|max:255',
            // 'phone'    => 'nullable|string|max:11',
            // 'email'    => 'nullable|email|unique:clients,email',
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

        $client = Client::create([
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
            'bank_account_name'    => $request->bank_account_name,
            'bank_account_number'    => $request->bank_account_number,
            'bank_routing_number'    => $request->bank_routing_number,
            'bin' => $request->bin,
            'password' => $request->password ? Hash::make($request->password) : null,
            'status'   => $request->status ?? 1,
        ]);

        //return redirect()->route('admin.supplier.index')->with('success', 'Supplier added successfully.');
        return response()->json([
            'success'  => true,
            'message'  => 'Client added successfully.',
            'client' => $client, // Send back the created client data
        ]);
        
    }

    public function AdminClientUpdate(Request $request, $id)
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
            'status' => 'nullable|boolean'
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $client = Client::findOrFail($id);

        // Update the supplier data
        $client->update([
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
            'bank_account_name'    => $request->input('bank_account_name'),
            'bank_account_number'    => $request->input('bank_account_number'),
            'bank_routing_number'    => $request->input('bank_routing_number'),
            'bin' => $request->input('bin'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : null, // Only update password if provided
            'status' => $request->input('status') ?? true, // Set default active value if not provided
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.client.index')->with('success', 'Client updated successfully!');
    }

    public function AdminClientDestroy($id)
    {
        // Find the client by ID
        $client = Client::findOrFail($id);

        // Delete the client record
        $client->delete();

        // Redirect back to the client index with a success message
        return redirect()->route('admin.client.index')->with('success', 'Client deleted successfully!');
    }

    public function viewProducts($clientId)
    {
        // Fetch the client
        $client = Client::findOrFail($clientId);

        // Fetch the purchased products for this supplier
        $purchasedProducts = Sale::where('client_id', $clientId)
                                    ->with('products') // Assuming Purchase has a relation with products
                                    ->get();

        //dd($purchasedProducts);

        $pageTitle = 'Saled Products History';

        $totalPurchaseAmount = $client->totalPurchaseAmount();
        //dd($totalPurchaseAmount);
        $totalPaidAmount = $client->totalPaidAmount();
        // dd($totalPaidAmount);
        $totalDueAmount = $client->totalDueAmount();
        //dd($totalDueAmount);
        $totalDiscounta = $client->totalDiscount();
        //dd($totalDiscounta);

        // Return the view for purchased products
        return view('backend.admin.inventory.client.products', compact('pageTitle','client', 'purchasedProducts','totalPurchaseAmount','totalDiscounta','totalPaidAmount','totalDueAmount'));
    }

    public function viewTransactions($clientId)
    {
        // Fetch the client
        $client = client::findOrFail($clientId);

        // Fetch the transactions/payments related to this supplier
        $transactions = Sale::where('client_id', $clientId)->get();

        $pageTitle = 'client Transactions';

        // Return the view for transactions
        return view('backend.admin.inventory.client.transactions', compact('pageTitle','client', 'transactions'));
    }
}
