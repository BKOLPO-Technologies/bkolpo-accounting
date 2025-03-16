<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\Journal;
use App\Models\JournalVoucher;
use App\Models\LedgerGroup;
use App\Models\JournalVoucherDetail;
use App\Models\CompanyInformation;
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

    // balance Sheet report
    public function balanceSheet(Request $request)
    {
        $pageTitle = 'Balance Sheet Report';
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $ledgerGroups = LedgerGroup::with([
            'subGroups.ledgers' => function ($query) use ($fromDate, $toDate) {
                $query->withSum(['journalVoucherDetails as total_debit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                        ->whereDate('created_at', '<=', $toDate);
                }], 'debit')
                ->withSum(['journalVoucherDetails as total_credit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                        ->whereDate('created_at', '<=', $toDate);
                }], 'credit');
            }
        ])->orderBy('id', 'ASC')->get();

        return view('backend.admin.report.account.balance_sheet', compact('pageTitle', 'ledgerGroups', 'fromDate', 'toDate'));
    }


    // ledger list
    public function ledgerList()
    {
        $pageTitle = 'Ledger List';
        $ledgers = Ledger::with(['journalVoucherDetails'])->get();
        return view('backend.admin.report.account.ledger_list',compact('pageTitle','ledgers'));
    }

    // ledger report
    public function ledgerReport(Request $request, $id)
    {
        $pageTitle = 'Ledger Report';

        // Set default date range (last 1 month)
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Load ledger WITHOUT journalVoucherDetails, but with filtered transactions
        $ledger = Ledger::with([
            'journalVoucherDetails' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
            }
        ])->findOrFail($id);

        return view('backend.admin.report.account.ledger_report', compact('pageTitle', 'ledger', 'fromDate', 'toDate'));
    }

    // ledger group list
    public function ledgerGroupList()
    {
        $pageTitle = 'Ledger Group List';
        $ledgerGroups = LedgerGroup::latest()->orderBy('id', 'DESC')->get();
        return view('backend.admin.report.account.ledger_group_list',compact('pageTitle','ledgerGroups'));
    }

    // ledger group report
    public function ledgerGroupReport(Request $request, $id)
    {
        $pageTitle = 'Ledger Group Report';

       // Set default date range (last 1 month)
       $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
       $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Fetch ledger groups with their ledgers and calculate balances
        $ledgerGroup = LedgerGroup::with([
            'subGroups.ledgers' => function ($query) use ($fromDate, $toDate) {
                $query->withSum(['journalVoucherDetails as total_debit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                }], 'debit')
                ->withSum(['journalVoucherDetails as total_credit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                          ->whereDate('created_at', '<=', $toDate);
                }], 'credit');
            }
        ])
        ->orderBy('id', 'DESC')
        ->findOrFail($id);
        

        // dd($ledgerGroup);
        return view('backend.admin.report.account.ledger_group_report',compact('pageTitle','ledgerGroup','fromDate','toDate'));
    }

    // ledger payslip
    public function getLedgerPaySlip($id) {
        $ledger = Ledger::with('journalVoucherDetails')->find($id);
        
        if (!$ledger) {
            return response("<p class='text-danger'>Ledger not found.</p>", 404);
        }
    
        $company = CompanyInformation::first();
        return view('backend.admin.report.account.ledger_pay_slip', compact('ledger','company'));
    }

    // profit & loss report
    public function ledgerProfitLoss(Request $request)
    {
        $pageTitle = 'Profit & Loss Report';

        // Define date range
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Fetch Ledger Groups with their Ledgers and sum up debit & credit
        $ledgerGroups = LedgerGroup::with([
            'ledgers' => function ($query) use ($fromDate, $toDate) {
                $query->withSum(['journalVoucherDetails as total_debit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
                }], 'debit')
                ->withSum(['journalVoucherDetails as total_credit' => function ($query) use ($fromDate, $toDate) {
                    $query->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
                }], 'credit');
            }
            
        ])->orderBy('id', 'ASC')
        ->get();

        // Calculate total debit, credit, and net profit/loss
        $totalDebit = $ledgerGroups->sum(fn($group) => $group->ledgers->sum('total_debit'));
        $totalCredit = $ledgerGroups->sum(fn($group) => $group->ledgers->sum('total_credit'));
        $netProfitLoss = $totalCredit - $totalDebit;

        return view('backend.admin.report.account.profit_loss_report', compact(
            'pageTitle', 'fromDate', 'toDate', 'ledgerGroups', 'totalDebit', 'totalCredit', 'netProfitLoss'
        ));
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
