<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function AdminSupplierIndex() {

        $pageTitle = 'Admin Supplier';
        return view('backend/admin/supplier/index',compact('pageTitle'));

    }

    public function AdminSupplierCreate() {

        $pageTitle = 'Admin Supplier Create';
        return view('backend/admin/supplier/create',compact('pageTitle'));

    }

    public function AdminSupplierView() {

        $pageTitle = 'Admin Supplier View';
        return view('backend/admin/supplier/view',compact('pageTitle'));

    }

    public function AdminSupplierEdit() {
        $pageTitle = 'Admin Supplier Edit';
        return view('backend/admin/supplier/edit',compact('pageTitle'));
    }

}
