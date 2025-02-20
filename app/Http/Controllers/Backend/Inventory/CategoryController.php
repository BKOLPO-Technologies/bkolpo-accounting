<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function AdminCategoryIndex() 
    {
        $categories = Category::all();
        $pageTitle = 'Admin Category';
        return view('backend/admin/inventory/category/index',compact('pageTitle', 'categories'));

    }
}
