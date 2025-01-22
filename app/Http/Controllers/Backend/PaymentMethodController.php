<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;

class PaymentMethodController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Payment Method List';

        $payments = PaymentMethod::latest()->get();
        return view('backend.admin.payment.index',compact('pageTitle','payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Payment Method Create';
        return view('backend.admin.payment.create',compact('pageTitle'));
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
        ]);

        // Create the payment record
        $payment = PaymentMethod::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        return redirect()->route('payment.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = PaymentMethod::findOrFail($id);

        $pageTitle = 'Payment Method View';
        return view('backend.admin.payment.show', compact('payment','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = PaymentMethod::findOrFail($id);

        $pageTitle = 'Payment Method Edit';
        return view('backend.admin.payment.edit', compact('payment','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $payment = PaymentMethod::findOrFail($id);

        $payment->name = $request->input('name');
        $payment->status = $request->input('status');
        $payment->save();

        return redirect()->route('payment.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = PaymentMethod::find($id);
        $payment->delete();
        
        return redirect()->route('payment.index')->with('success', 'Payment deleted successfully.');
    }
}
