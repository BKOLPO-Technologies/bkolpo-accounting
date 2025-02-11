<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\JournalVoucher;
use App\Models\LedgerGroup;
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
    public function trialBalance(Request $request)
    {
        // dd($request->all());
        $pageTitle = 'Trial Balance Report';

        // Check if the request has date filters
        if ($request->has('from_date') && $request->has('to_date')) {
            // Use the provided date range
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
        } else {
            // Default: Last 1 month from today
            $fromDate = now()->subMonth()->format('Y-m-d'); // Last 1 month
            $toDate = now()->format('Y-m-d'); // Today's date
        }
        // Fetch the trial balance data based on the date range
        $trialBalances = $this->getTrialBalance($fromDate, $toDate);
        return view('backend.admin.report.account.trial_balance', compact('pageTitle', 'trialBalances', 'fromDate', 'toDate'));
    }

    // balance shit report
    public function balanceShit(Request $request)
    {
        // dd($request->all());
        $pageTitle = 'Balance Shit Report';

        return view('backend.admin.report.account.balance_shit', compact('pageTitle'));
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
