<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function AdminLedgerGroup(){

        $pageTitle = 'Admin Ledger Group';
        return $pageTitle;

    }

    public function AdminLedgerGroupCreate(){

        $pageTitle = 'Admin Ledger Group Create';
        return $pageTitle;

    }

    public function AdminLedgerGroupTrashed(){

        $pageTitle = 'Admin Ledger Group Trashed';
        return $pageTitle;

    }
}
