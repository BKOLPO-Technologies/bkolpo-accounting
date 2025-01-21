<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function AdminTransactionIndex() 
    {

        $pageTitle = 'Admin Transaction';
        return view('backend/admin/transaction/index',compact('pageTitle'));

    }

    public function AdminTransactionAdd() 
    {

        $pageTitle = 'Admin Transaction Add';
        return view('backend/admin/transaction/add',compact('pageTitle'));

    }

    public function AdminTransactionTransfer() 
    {

        $pageTitle = 'Admin Transaction Transfer';
        return view('backend/admin/transaction/transfer',compact('pageTitle'));

    }

    public function AdminTransactionIncome() 
    {

        $pageTitle = 'Admin Transaction Income';
        return view('backend/admin/transaction/income',compact('pageTitle'));

    }

    public function AdminTransactionExpense() 
    {

        $pageTitle = 'Admin Transaction Expense';
        return view('backend/admin/transaction/expense',compact('pageTitle'));

    }

}
