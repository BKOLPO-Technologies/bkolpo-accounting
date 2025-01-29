<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Report Index';

        return view('backend.admin.report.account.index',compact('pageTitle'));
    }

    /**
     * Display a listing of the resource.
     */
    public function generalLedger()
    {
        $pageTitle = 'Report General Ledger';

        // Example data
        $ledgerData = [
            [
                'section' => 'Opening Balance Equity',
                'entries' => [
                    ['date' => '28-01-2025', 'type' => 'Deposit', 'description' => 'aaaa', 'amount' => 111.00, 'balance' => 111.00],
                ],
                'total' => 111.00,
            ],
            [
                'section' => 'Office Expenses',
                'entries' => [
                    ['date' => '29-01-2025', 'type' => 'Journal Entry', 'description' => 'Split', 'amount' => 20000.00, 'balance' => 20000.00],
                ],
                'total' => 20000.00,
            ],
            [
                'section' => 'Reconciliation Discrepancies',
                'entries' => [
                    ['date' => '29-01-2025', 'type' => 'Journal Entry', 'description' => 'Split', 'amount' => -20000.00, 'balance' => -20000.00],
                    ['date' => '28-01-2025', 'type' => 'Deposit', 'description' => 'aaaa', 'amount' => 111.00, 'balance' => 111.00],
                ],
                'total' => -19889.00,
            ],
        ];

        return view('backend.admin.report.account.generalLedger',compact('pageTitle', 'ledgerData'));
    }
}
