<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerSubGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Ledger Sub Group List';

        //$ledgers = LedgerGroup::latest()->get();
        return view('backend.admin.ledger.subgroup.index',compact('pageTitle'));
    }
}
