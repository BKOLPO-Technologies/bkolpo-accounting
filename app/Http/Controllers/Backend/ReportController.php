<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Carbon\Carbon;
use Auth;
use App\Traits\TrialBalanceTrait;

class ReportController extends Controller
{
    use TrialBalanceTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Report List';
        return view('backend.admin.report.account.index',compact('pageTitle'));
    }

    // trial balance report
    public function trialBalance()
    {
        $pageTitle = 'Trial Balance Report';
        $trialBalances = $this->getTrialBalance();
        return view('backend.admin.report.account.trial_balance',compact('pageTitle','trialBalances'));
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
