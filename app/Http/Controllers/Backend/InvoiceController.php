<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function AdminInvoiceIndex() {

        $pageTitle = 'Admin Invoice';
        return view('backend/admin/invoice/index',compact('pageTitle'));

    }

    public function AdminInvoiceDetails() {

        $pageTitle = 'Admin Bank Cash';
        return view('backend/admin/invoice/invoice',compact('pageTitle'));

    }
    
    public function AdminInvoiceCreate() {

        $pageTitle = 'Admin Invoice Create';
        return view('backend/admin/invoice/create',compact('pageTitle'));

    }

}
