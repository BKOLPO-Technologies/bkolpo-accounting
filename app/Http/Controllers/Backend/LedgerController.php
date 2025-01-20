<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function AdminLedgerGroup() {

        $pageTitle = 'Admin Ledger Group';
        return view('backend/admin/ledger/group/index',compact('pageTitle'));

    }

    public function AdminLedgerGroupCreate() {

        $pageTitle = 'Admin Ledger Group Create';
        return view('backend/admin/ledger/group/create',compact('pageTitle'));

    }

    public function AdminLedgerGroupTrashed() {

        $pageTitle = 'Admin Ledger Group Trashed';
        return view('backend/admin/ledger/group/trashed',compact('pageTitle'));

    }

    ////////// Name ///////////////

    public function AdminLedgerName() {

        $pageTitle = 'Admin Ledger Name';
        return view('backend/admin/ledger/name/index',compact('pageTitle'));

    }

    public function AdminLedgerNameCreate() {

        $pageTitle = 'Admin Ledger Name Create';
        return view('backend/admin/ledger/name/create',compact('pageTitle'));

    }

    public function AdminLedgerNameTrashed() {

        $pageTitle = 'Admin Ledger Name Trashed';
        return view('backend/admin/ledger/name/trashed',compact('pageTitle'));

    }
}
