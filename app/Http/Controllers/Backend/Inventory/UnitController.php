<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function AdminUnitIndex() 
    {
        $units = Unit::all();
        $pageTitle = 'Product Unit';
        return view('backend.admin.inventory.unit.index',compact('pageTitle', 'units'));
    }

    public function AdminUnitCreate() 
    {
        $pageTitle = 'Product Unit Create';
        return view('backend.admin.inventory.unit.create',compact('pageTitle'));
    }


    public function AdminUnitStore(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Store the category with the unique slug
        Unit::create([
            'name' => $request->name,
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return redirect()->route('admin.unit.index')->with('success', 'Unit created successfully!');
    }
    
    public function AdminUnitEdit($id)
    {
        $unit = Unit::findOrFail($id);
        $pageTitle = 'Product Unit Edit';
        return view('backend.admin.inventory.unit.edit',compact('pageTitle', 'unit'));
    }

    public function AdminUnitUpdate(Request $request, $id)
    {
        //dd($request->all());

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $unit = Unit::findOrFail($id);

        // Update the supplier data
        $unit->update([
            'name' => $request->input('name'),
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.unit.index')->with('success', 'Unit updated successfully!');
    }

    public function AdminUnitDestroy($id)
    {
        // Find the supplier by ID
        $unit = Unit::findOrFail($id);

        // Delete the supplier record
        $unit->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.unit.index')->with('success', 'Unit deleted successfully!');
    }
}
