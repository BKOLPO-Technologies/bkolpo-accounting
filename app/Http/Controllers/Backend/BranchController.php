<?php

namespace App\Http\Controllers\Backend;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    public function AdminBranch()
    {
        $branches = Branch::all();
        //dd($branches);
        $pageTitle = 'Admin Branch';
        return view('backend/admin/branch/index', compact('pageTitle', 'branches'));
    }

    public function AdminCreate()
    {
        $pageTitle = 'Admin Create';
        return view('backend/admin/branch/create',compact('pageTitle'));
    }

    public function AdminTrashed()
    {
        $pageTitle = 'Admin Trashed';
        return view('backend/admin/branch/trashed',compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Branch::create($request->all());

        return redirect()->route('branch.admin.branch')->with('success', 'Branch created successfully.');
    }

    public function destroy(Branch $branch)
    {
        // Delete the branch
        $branch->delete();

        // Redirect back with a success message
        return redirect()->route('branch.admin.branch')->with('success', 'Branch deleted successfully.');
    }

    public function edit(Branch $branch)
    {
        return view('backend.admin.branch.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update the branch
        $branch->update($request->only('name', 'location', 'description'));

        // Redirect back with a success message
        return redirect()->route('branch.admin.branch')->with('success', 'Branch updated successfully.');
    }



    
}
