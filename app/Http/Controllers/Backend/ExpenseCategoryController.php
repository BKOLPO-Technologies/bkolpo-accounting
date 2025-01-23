<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger List';

        $expensecategories = ExpenseCategory::latest()->get();
        return view('backend.admin.expense.category.index',compact('pageTitle','expensecategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Ledger Create';
        return view('backend.admin.expense.category.create',compact('pageTitle'));
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

        // Create the expensecateory record
        $expensecateory = ExpenseCategory::create([
            'name'          => $request->name,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        return redirect()->route('expense-category.index')->with('success', 'Ledger created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expensecateory = ExpenseCategory::findOrFail($id);

        $pageTitle = 'Ledger View';
        return view('backend.admin.expense.category.show', compact('expensecateory','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expensecateory = ExpenseCategory::findOrFail($id);

        $pageTitle = 'Ledger Edit';
        return view('backend.admin.expense.category.edit', compact('expensecateory','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $expensecateory = ExpenseCategory::findOrFail($id);

        $expensecateory->name = $request->input('name');
        $expensecateory->status = $request->input('status');
        $expensecateory->save();

        return redirect()->route('expense-category.index')->with('success', 'Ledger updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expensecateory = ExpenseCategory::find($id);
        $expensecateory->delete();
        
        return redirect()->route('expense-category.index')->with('success', 'Ledger deleted successfully.');
    }
}
