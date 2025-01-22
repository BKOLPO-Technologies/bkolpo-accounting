<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;


class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Bank List';

        $banks = Bank::latest()->get();
        return view('backend.admin.bank.index',compact('pageTitle','banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Bank Create';
        return view('backend.admin.bank.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required',
            'account_number' => 'nullable|string',
        ]);

        // Create the Bank record
        $bank = Bank::create([
            'name'          => $request->name,
            'account_number'=> $request->account_number,
            'description'   => $request->description,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        return redirect()->route('bank.index')->with('success', 'Bank created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank = Bank::findOrFail($id);

        $pageTitle = 'Bank View';
        return view('backend.admin.bank.show', compact('bank','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bank = Bank::findOrFail($id);

        $pageTitle = 'Bank Edit';
        return view('backend.admin.bank.edit', compact('bank','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'location' => 'nullable|string',
        ]);

        $bank = Bank::findOrFail($id);

        $bank->name = $request->input('name');
        $bank->account_number = $request->input('account_number');
        $bank->status = $request->input('status');
        $bank->description = $request->input('description', ''); 
        $bank->save();

        return redirect()->route('bank.index')->with('success', 'Bank updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank = Bank::find($id);
        $bank->delete();
        
        return redirect()->route('bank.index')->with('success', 'Bank deleted successfully.');
    }
}
