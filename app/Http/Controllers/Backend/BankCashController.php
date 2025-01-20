<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankCashController extends Controller
{
    

    public function AdminBankCash() {

        $pageTitle = 'Admin Bank Cash';
        return view('backend/admin/bankcash/index',compact('pageTitle'));

    }

    public function AdminBankCashCreate() {

        $pageTitle = 'Admin Bank Cash Create';
        return view('backend/admin/bankcash/create',compact('pageTitle'));

    }

    public function AdminBankCashTrashed() {

        $pageTitle = 'Admin Bank Cash Trashed';
        return view('backend/admin/bankcash/trashed',compact('pageTitle'));

    }
}
