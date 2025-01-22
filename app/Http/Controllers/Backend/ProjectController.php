<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function AdminProjectIndex() {

        $pageTitle = 'Admin Project';
        return view('backend/admin/project/index',compact('pageTitle'));

    }

    public function AdminProjectCreate() 
    {
        $customers = Customer::all();
        $pageTitle = 'Admin Project Create';
        return view('backend/admin/project/create',compact('pageTitle', 'customers'));

    }
}
