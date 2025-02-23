<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function AdminProductIndex() 
    {
        $products = Product::all();
        $pageTitle = 'Admin Product';
        return view('backend/admin/inventory/product/index',compact('pageTitle', 'products'));
    }

    public function AdminProductCreate() 
    {
        $pageTitle = 'Admin Product Create';
        $categories = Category::where('status',1)->latest()->get();
        return view('backend/admin/inventory/product/create',compact('pageTitle','categories'));
    }

    public function AdminProductStore(Request $request)
    {
        //dd($request->all());
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Store the product with the validated data
        $product =  Product::create([
            'name' => $request->name,
            'price' => $request->price ?? 0, // Store null if not provided
            'description' => $request->description ?? null, // Store null if not provided
            'quantity' => $request->quantity,
            'status' => $request->status ?? 1, // Default to active if not provided
            'category_id' => $request->category_id,
        ]);

        if ($request->hasFile('image')) {
            @unlink(public_path('upload/inventory/products' . $company->image)); // Delete old logo
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/inventory/products'), $filename);
            $product->image = $filename;
        }
        
        $product->save();

        // Redirect to a product list page or any other route you prefer with a success message
        return redirect()->route('admin.product.index')->with('success', 'Product created successfully!');
    }

    
    public function AdminProductEdit($id)
    {
        $product = Product::findOrFail($id);
        $pageTitle = 'Admin Product Edit';
        $categories = Category::where('status',1)->latest()->get();
        return view('backend/admin/inventory/product//edit',compact('pageTitle', 'product','categories'));
    }

    public function AdminProductUpdate(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
            // // Store new image
            // $imagePath = $request->file('image')->store('inventory/products', 'public');

            // // Optionally delete the old image if exists
            // if ($product->image) {
            //     Storage::delete('public/' . $product->image);
            // }

            // $product->image = $imagePath;
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Store new image
            $imagePath = $request->file('image')->store('inventory/products', 'public');
            $product->image = $imagePath;
        }

        // Update the product data
        $product->update([
            'name' => $request->input('name'),
            'price' => $request->input('price', $product->price), // Keep existing price if not provided
            'description' => $request->input('description', $product->description), // Keep existing description
            'quantity' => $request->input('quantity', $product->quantity), // Keep existing quantity
            'active' => $request->has('active') ? $request->input('active') : $product->active, // Keep existing status
            'image' => $product->image,
        ]);

        // Redirect back to the product index with a success message
        return redirect()->route('admin.product.index')->with('success', 'Product updated successfully!');
    }


    public function AdminProductDestroy($id)
    {
        // Find the supplier by ID
        $product = Product::findOrFail($id);

        // Delete the product image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the supplier record
        $product->delete();

        // Redirect back to the supplier index with a success message
        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully!');
    }
}
