<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
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
use App\Traits\SalesReportTrait;
use App\Traits\PurchasesReportTrait;
use App\Traits\PurchaseSalesReportTrait;
use App\Traits\BillsPayableReportTrait;
use App\Traits\BillsReceivableReportTrait;
use App\Traits\HasReceiptPaymentReport;

class ReportController extends Controller
{
    use TrialBalanceTrait;
    use ProjectProfitLossTrait;
    use SalesReportTrait;
    use PurchasesReportTrait;
    use PurchaseSalesReportTrait;
    use BillsPayableReportTrait;
    use BillsReceivableReportTrait;
    use HasReceiptPaymentReport;

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

    // sales report
    public function salesReport(Request $request)
    {
        $pageTitle = 'Sales Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $salesReports = $this->getSalesReport($fromDate, $toDate);

        return view('backend.admin.report.account.sales_report', compact('pageTitle', 'salesReports', 'fromDate', 'toDate'));
    }

    // sales report
    public function purchasesReport(Request $request)
    {
        $pageTitle = 'Purchases Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $purchasesReports = $this->getpurchasesReport($fromDate, $toDate);

        return view('backend.admin.report.account.purchases_report', compact('pageTitle', 'purchasesReports', 'fromDate', 'toDate'));
    }
    
    // purchases and sales report
    public function purchasesSalesReport(Request $request)
    {
        $pageTitle = 'Purchases & Sales Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $purchases = $this->getPurchasesReport2($fromDate, $toDate);
        $sales = $this->getSalesReport2($fromDate, $toDate);

        // $purchasesSalesReports = $purchases->merge($sales)->sortBy('invoice_date');

        $purchasesSalesReports = $purchases->merge($sales)->sortBy([
            ['type', 'asc'],
            ['invoice_date', 'asc'],
        ]);

        return view('backend.admin.report.account.purchases_sales_report', compact(
            'pageTitle',
            'purchasesSalesReports',
            'fromDate',
            'toDate'
        ));
    }

    // bills payable
    public function billsPayableReport(Request $request)
    {
        $pageTitle = 'Bills Payable Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $billspayableReports = $this->getBillsPayableReport($fromDate, $toDate);

        return view('backend.admin.report.account.billspayable_report', compact(
            'pageTitle',
            'billspayableReports',
            'fromDate',
            'toDate'
        ));
    }

    // bills receivable
    public function billsReceivableReport(Request $request)
    {
        $pageTitle = 'Bills Receivable Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $billsReceivableReports = $this->getBillsReceivableReport($fromDate, $toDate);

        return view('backend.admin.report.account.billsreceivable_report', compact(
            'pageTitle',
            'billsReceivableReports',
            'fromDate',
            'toDate'
        ));
    }

    // group wise report
    public function groupwiseReport(Request $request)
    {
        $pageTitle = 'Ledger Group → Subgroup → Ledger Statement';

        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', now()->toDateString());
        $nameSearch = trim($request->input('name'));

        $groupsQuery = LedgerGroup::with(['subGroups.ledgers.journalVoucherDetails' => function ($q) use ($fromDate, $toDate) {
            $q->whereHas('journalVoucher', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('transaction_date', [$fromDate, $toDate]);
            });
        }]);

        if ($nameSearch) {
            $groupsQuery->where(function ($query) use ($nameSearch) {
                $query->where('group_name', 'LIKE', "%{$nameSearch}%")
                    ->orWhereHas('subGroups', function ($subQuery) use ($nameSearch) {
                        $subQuery->where('subgroup_name', 'LIKE', "%{$nameSearch}%")
                            ->orWhereHas('ledgers', function ($ledgerQuery) use ($nameSearch) {
                                $ledgerQuery->where('name', 'LIKE', "%{$nameSearch}%");
                            });
                    });
            });
        }

        $groups = $groupsQuery->get();

        return view('backend.admin.report.account.groupwise_statement', compact('pageTitle', 'groups', 'fromDate', 'toDate'));
    }

    // receipt payment
    public function receiptPaymentReport(Request $request)
    {
        $pageTitle = 'Receipt & Payment Report';

        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        $transactions = $this->getReceiptPaymentTransactions($fromDate, $toDate);

        // dd($transactions);

        return view('backend.admin.report.account.receiptpayment_report', compact(
            'pageTitle',
            'transactions',
            'fromDate',
            'toDate'
        ));
    }
    


    

    // balance Sheet report
    public function balanceSheet(Request $request)
    {
        $pageTitle = 'Balance Sheet Report';
        $fromDate = $request->input('from_date', now()->subMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // Get only Asset and Liability groups
        $ledgerGroups = LedgerGroup::whereIn('group_name', ['Asset', 'Liabilities'])
            ->with([
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
            ->orderByRaw("FIELD(group_name, 'Asset', 'Liabilities')")
            ->get();

        // Calculate totals
        $totalAssets = 0;
        $totalLiabilities = 0;

        foreach ($ledgerGroups as $group) {
            foreach ($group->subGroups as $subGroup) {
                foreach ($subGroup->ledgers as $ledger) {
                    $balance = abs($ledger->total_debit - $ledger->total_credit);
                    if ($group->group_name == 'Asset') {
                        $totalAssets += $balance;
                    } else {
                        $totalLiabilities += $balance;
                    }
                }
            }
        }

        // Calculate difference (positive if assets > liabilities)
        $difference = $totalAssets - $totalLiabilities;

        // Determine which side needs the difference
        $showDifferenceOn = $difference > 0 ? 'Liabilities' : 'Asset';
        $absDifference = abs($difference);

        return view('backend.admin.report.account.balance_sheet', compact(
            'pageTitle',
            'ledgerGroups',
            'fromDate',
            'toDate',
            'totalAssets',
            'totalLiabilities',
            'difference',
            'showDifferenceOn',
            'absDifference'
        ));
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

   public function showDayBook(Request $request)
    {
        $pageTitle = 'Day Book Report';

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$fromDate || !$toDate) {
            // Default to today's report
            $fromDate = $toDate = now()->toDateString();
        }

        $transactions = JournalVoucher::with('details.ledger')
            ->whereBetween('transaction_date', [$fromDate, $toDate])
            ->orderBy('transaction_date')
            ->get();

        return view('backend.admin.report.account.daybook_report', compact(
            'pageTitle', 'transactions', 'fromDate', 'toDate'
        ));
    }


    public function showContra(Request $request)
    {
        $pageTitle = 'Contra Report';

        return view('backend.admin.report.account.contra_report', compact(
            'pageTitle'
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
