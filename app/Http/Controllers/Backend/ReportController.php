<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use App\Models\Project;
use App\Models\Journal;
use App\Models\JournalVoucher;
use App\Models\LedgerGroup;
use App\Models\LedgerSubGroup;
use App\Models\JournalVoucherDetail;
use App\Models\CompanyInformation;
use Carbon\Carbon;
use Auth;
use App\Traits\TrialBalanceTrait;
use App\Traits\ProjectProfitLossTrait;

class ReportController extends Controller
{
    use TrialBalanceTrait;
    use ProjectProfitLossTrait;

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
        
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $withLedgers = [
            'ledgers' => function ($query) use ($fromDate, $toDate) {
                $query->withSum(['journalVoucherDetails as total_debit' => function ($q) use ($fromDate, $toDate) {
                    $q->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
                }], 'debit')
                ->withSum(['journalVoucherDetails as total_credit' => function ($q) use ($fromDate, $toDate) {
                    $q->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate);
                }], 'credit');
            }
        ];

        
        // Fetch the first subgroup for Sales Account
        $salesAccount = LedgerSubGroup::where('subgroup_name', 'Sales Account')
            ->with($withLedgers)
            ->get();

        $cogsAccount = LedgerSubGroup::where('subgroup_name', 'Cost of Goods Sold')
            ->with($withLedgers)
            ->get();

        $operatingExpensesAccount = LedgerSubGroup::where('subgroup_name', 'Operating Expense')
            ->with($withLedgers)
            ->get();

        $nonOperatingItemsAccount = LedgerSubGroup::where('subgroup_name', 'Non-Operating Items')
            ->with($withLedgers)
            ->get();
        
        return view('backend.admin.report.account.profit_loss_report', compact(
            'pageTitle','fromDate','toDate', 'salesAccount', 'cogsAccount', 'operatingExpensesAccount', 'nonOperatingItemsAccount'
        ));
    }

    

    // project profit & loss report

    public function projectProfitLoss(Request $request)
{
    $pageTitle = 'Project Profit & Loss Report';
    $allProjects = Project::all();

    // Date Range
    $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
    $toDate = $request->input('to_date', now()->format('Y-m-d'));
    $projectId = $request->input('project_id');

    // Projects Query (Initially Empty)
    $projects = collect();
    $totalSales = 0;
    $totalPurchases = 0;
    $netProfitLoss = 0;

    if ($projectId) {
        // Fetch only when project is selected
        $projects = Project::with(['purchases' => function ($query) use ($fromDate, $toDate) {
            if ($fromDate && $toDate) {
                // If both fromDate and toDate are provided, filter purchases within that range
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            } elseif (!$fromDate || !$toDate || $toDate == now()->format('Y-m-d')) {
                // If either fromDate or toDate is missing, or toDate is today, get purchases after toDate
                $query->orWhere('created_at', '>', $toDate);
            }
        }])->where('id', $projectId)->get();
        

        // Calculate total sales & purchases
        $totalSales = $projects->sum('grand_total');
        $totalPurchases = $projects->sum(fn ($project) => $project->purchases->sum('total'));
        $netProfitLoss = $totalSales - $totalPurchases;
    }

    return view('backend.admin.report.account.project_profit_loss_report', compact(
        'pageTitle', 'fromDate', 'toDate', 'projects', 'totalSales', 'totalPurchases','allProjects', 'netProfitLoss'
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
