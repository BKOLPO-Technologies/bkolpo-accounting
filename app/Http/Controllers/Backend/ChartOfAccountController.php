<?php

namespace App\Http\Controllers\Backend;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Chart of account';

        return view('backend.admin.voucher.chart_of_account.index',compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create chart of account';
        $companies = Company::where('status',1)->latest()->get();

        return view('backend.admin.voucher.chart_of_account.create',compact('pageTitle', 'companies'));
    }
}
