<?php

namespace App\Http\Controllers\Backend;

use DB;
use Hash;
use Carbon\Carbon;
use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Branch List';

        $branchs = Branch::latest()->get();
        return view('backend.admin.branch.index',compact('pageTitle','branchs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Branch Create';
        return view('backend.admin.branch.create',compact('pageTitle'));
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
            'location' => 'nullable|string',
        ]);

        // Create the branch record
        $branch = Branch::create([
            'name'          => $request->name,
            'location'      => $request->location,
            'description'   => $request->description,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        return redirect()->route('branch.index')->with('success', 'Branch created successfully.');
    }

    public function store2(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'location' => 'nullable|string',
        ]);

        // Create the branch record
        $branch = Branch::create([
            'name'          => $request->name,
            'location'      => $request->location,
            'description'   => $request->description,
            'status'        => $request->status,
            'created_by'    => Auth::user()->id,
        ]);

        //return redirect()->route('admin.supplier.index')->with('success', 'Supplier added successfully.');
        return response()->json([
            'success'  => true,
            'message'  => 'Brunch added successfully.',
            'branch' => $branch, // Send back the created supplier data
        ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);

        $pageTitle = 'Branch View';
        return view('backend.admin.branch.show', compact('branch','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branch = Branch::findOrFail($id);

        $pageTitle = 'Branch Edit';
        return view('backend.admin.branch.edit', compact('branch','pageTitle'));
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

        $branch = Branch::findOrFail($id);

        $branch->name = $request->input('name');
        $branch->location = $request->input('location');
        $branch->status = $request->input('status');
        $branch->description = $request->input('description', ''); 
        $branch->save();

        return redirect()->route('branch.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $branch = Branch::find($id);
        $branch->delete();
        
        return redirect()->route('branch.index')->with('success', 'Branch deleted successfully.');
    }
}
