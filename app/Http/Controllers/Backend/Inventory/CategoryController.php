<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function AdminCategoryIndex() 
    {
        $categories = Category::all();
        $pageTitle = 'Admin Category';
        return view('backend.admin.inventory.category.index',compact('pageTitle', 'categories'));
    }

    public function AdminCategoryCreate() 
    {
        $pageTitle = 'Admin Category Create';
        return view('backend.admin.inventory.category.create',compact('pageTitle'));
    }


    public function AdminCategoryStore(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Check if slug already exists and append a number if necessary
        $existingCategory = Category::where('slug', $slug)->first();
        if ($existingCategory) {
            $slug = $slug . '-' . (Category::count() + 1);
        }

        // Store the category with the unique slug
        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $request->image ?? null, // Add logic for image if needed
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Category created successfully!');
    }
    
    public function AdminCategoryEdit($id)
    {
        $category = Category::findOrFail($id);
        $pageTitle = 'Admin Category Edit';
        return view('backend.admin.inventory.category.edit',compact('pageTitle', 'category'));
    }

    public function AdminCategoryUpdate(Request $request, $id)
    {

        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        //dd($request->company);

        // Find the supplier by ID
        $category = Category::findOrFail($id);

        // Update the supplier data
        $category->update([
            'name' => $request->input('name'),
        ]);

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully!');
    }

    public function AdminCategoryDestroy($id)
    {
        // Find the supplier by ID
        $category = Category::findOrFail($id);

        // Delete the supplier record
        $category->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully!');
    }

}
