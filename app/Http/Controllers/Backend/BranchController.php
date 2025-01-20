<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function AdminBranch(){

        $pageTitle = 'Admin Branch';
        return view('backend/admin/branch/index',compact('pageTitle'));

    }

    public function AdminCreate(){

        $pageTitle = 'Admin Create';
        return view('backend/admin/branch/create',compact('pageTitle'));

    }

    public function AdminTrashed(){

        $pageTitle = 'Admin Trashed';
        return view('backend/admin/branch/trashed',compact('pageTitle'));

    }
}
