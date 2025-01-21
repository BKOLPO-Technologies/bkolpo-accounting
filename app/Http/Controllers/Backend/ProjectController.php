<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function AdminProjectIndex() {

        $pageTitle = 'Admin Project';
        return view('backend/admin/project/index',compact('pageTitle'));

    }

    public function AdminProjectCreate() {

        $pageTitle = 'Admin Project Create';
        return view('backend/admin/project/create',compact('pageTitle'));

    }
}
