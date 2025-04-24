@extends('layouts.admin', ['pageTitle' => 'Profit & Loss Report'])

<!-- CSS to Hide Form on Print -->
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }

    .collapse-icon {
        cursor: pointer;
        font-size: 16px;
        margin-right: 10px;
    }
</style>

@section('admin')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('report.balance.sheet') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <!-- Print Button -->
                        <div class="text-right mt-3 mr-4">
                            <button class="btn btn-primary" onclick="printProfitLoss()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>

                        <div id="printable-area">
                            <div class="card-body">
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">{{ get_company()->name ?? '' }}</h2>
                                    <p class="mb-0"><strong>Profit & Loss Report</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <div class="card-body">
                                    <!-- Date Filter Form -->
                                    <div id="filter-form">
                                        <form action="{{ route('report.ledger.profit.loss') }}" method="GET" class="mb-3">
                                            <div class="row justify-content-center">
                                                <div class="col-md-3">
                                                    <label for="from_date">From Date:</label>
                                                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="to_date">To Date:</label>
                                                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                                </div>
                                                <!-- Clear Button -->
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <a href="{{ route('report.ledger.profit.loss') }}" class="btn btn-danger w-100">Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Profit & Loss Table -->
                                    <div class="row mb-5 mt-4">
                                        <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                            <div class="table-responsive">
                                                <table id="example10" border="1" class="table-striped table-bordered" cellpadding="5" cellspacing="0" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 80%;">Description</th>
                                                            <th style="width: 20%;">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            function calculateSubgroupTotal($ledgers) {
                                                                $total = 0;
                                                                foreach ($ledgers as $ledger) {
                                                                    $debit = $ledger->total_debit ?? 0;
                                                                    $credit = $ledger->total_credit ?? 0;
                                                                    $total += abs($debit - $credit);
                                                                }
                                                                return $total;
                                                            }
                                                        
                                                            // Calculate Sales Total
                                                            $sales = 0;
                                                            foreach ($salesAccount as $subgroup) {
                                                                $sales += calculateSubgroupTotal($subgroup->ledgers);
                                                            }
                                                        
                                                            // Calculate COGS
                                                            $cogs = 0;
                                                            foreach ($cogsAccount as $subgroup) {
                                                                $cogs += calculateSubgroupTotal($subgroup->ledgers);
                                                            }
                                                        
                                                            $grossProfit = $sales - $cogs;
                                                        
                                                            // Calculate Operating Expenses
                                                            $operatingExpenses = 0;
                                                            foreach ($operatingExpensesAccount as $subgroup) {
                                                                $operatingExpenses += calculateSubgroupTotal($subgroup->ledgers);
                                                            }
                                                        
                                                            $operatingIncome = $grossProfit - $operatingExpenses;
                                                        
                                                            // Calculate Interest Income - Interest Expense (Non-Operating)
                                                            $totalInterestIncome = 0;
                                                            $totalInterestExpense = 0;
                                                        
                                                            foreach ($nonOperatingItemsAccount as $subgroup) {
                                                                foreach ($subgroup->ledgers as $ledger) {
                                                                    $balance = abs(($ledger->total_debit ?? 0) - ($ledger->total_credit ?? 0));

                                                                    if (stripos($ledger->name, 'interest income') !== false) {
                                                                        $totalInterestIncome += $balance;
                                                                    } elseif (stripos($ledger->name, 'interest expense') !== false) {
                                                                        $totalInterestExpense += $balance;
                                                                    }
                                                                }
                                                            }

                                                            // dd($totalInterestIncome,$totalInterestExpense);
                                                        
                                                            $totalNonOperating = $totalInterestIncome - $totalInterestExpense;

                                                            // dd($totalNonOperating);
                                                            $netIncome = $operatingIncome - $totalNonOperating;
                                                        @endphp
                                                
                                                        <!-- Sales Account -->
                                                        @foreach($salesAccount as $subgroup)
                                                            @php
                                                                $subGroupTotal = calculateSubgroupTotal($subgroup->ledgers);
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <strong>
                                                                        <a data-toggle="collapse" href="#salesAccount{{ $subgroup->id }}" role="button">
                                                                            <i class="fa fa-chevron-down collapse-icon"></i> {{ $subgroup->subgroup_name }}
                                                                        </a>
                                                                    </strong>
                                                                </td>
                                                                <td><strong>{{ bdt() }} {{ number_format($subGroupTotal, 2) }}</strong></td>
                                                            </tr>
                                                            <tbody class="collapse" id="salesAccount{{ $subgroup->id }}">
                                                                @foreach($subgroup->ledgers as $ledger)
                                                                    @php
                                                                        $balance = abs(($ledger->total_debit ?? 0) - ($ledger->total_credit ?? 0));
                                                                    @endphp
                                                                    <tr>
                                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                        <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @endforeach
                                                
                                                        <!-- Cost of Goods Sold -->
                                                        @foreach($cogsAccount as $subgroup)
                                                            @php
                                                                $subGroupTotal = calculateSubgroupTotal($subgroup->ledgers);
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <strong>
                                                                        <a data-toggle="collapse" href="#cogsAccount{{ $subgroup->id }}" role="button">
                                                                            <i class="fa fa-chevron-down collapse-icon"></i> {{ $subgroup->subgroup_name }}
                                                                        </a>
                                                                    </strong>
                                                                </td>
                                                                <td><strong>{{ bdt() }} {{ number_format($subGroupTotal, 2) }}</strong></td>
                                                            </tr>
                                                            <tbody class="collapse" id="cogsAccount{{ $subgroup->id }}">
                                                                @foreach($subgroup->ledgers as $ledger)
                                                                    @php
                                                                        $balance = abs(($ledger->total_debit ?? 0) - ($ledger->total_credit ?? 0));
                                                                    @endphp
                                                                    <tr>
                                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                        <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @endforeach

                                                        <!-- Gross Profit -->
                                                        <tr>
                                                            <td><strong>Gross Profit (Sales - COGS)</strong></td>
                                                            <td>
                                                                <strong>{{ bdt() }} {{ number_format($grossProfit, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                
                                                        <!-- Operating Expenses -->
                                                        @foreach($operatingExpensesAccount as $subgroup)
                                                            @php
                                                                $subGroupTotal = calculateSubgroupTotal($subgroup->ledgers);
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <strong>
                                                                        <a data-toggle="collapse" href="#operatingExpensesAccount{{ $subgroup->id }}" role="button">
                                                                            <i class="fa fa-chevron-down collapse-icon"></i> {{ $subgroup->subgroup_name }}
                                                                        </a>
                                                                    </strong>
                                                                </td>
                                                                <td><strong>{{ bdt() }} {{ number_format($subGroupTotal, 2) }}</strong></td>
                                                            </tr>
                                                            <tbody class="collapse" id="operatingExpensesAccount{{ $subgroup->id }}">
                                                                @foreach($subgroup->ledgers as $ledger)
                                                                    @php
                                                                        $balance = abs(($ledger->total_debit ?? 0) - ($ledger->total_credit ?? 0));
                                                                    @endphp
                                                                    <tr>
                                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                        <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @endforeach
                                                    
                                                
                                                        <!-- Operating Income -->
                                                        <tr>
                                                            {{-- <td><strong>Operating Income (Gross Profit - Operating Expenses)</strong></td> --}}
                                                            <td><strong>Operating Income</strong></td>
                                                            <td>
                                                                <strong>{{ bdt() }} {{ number_format($operatingIncome, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                
                                                        <!-- Non-Operating Items -->
                                                        {{-- @php $totalNonOperating = 0; @endphp --}}
                                                        @foreach($nonOperatingItemsAccount as $subgroup)
                                                            {{-- @php
                                                                $subGroupTotal = calculateSubgroupTotal($subgroup->ledgers);
                                                                $totalNonOperating += $subGroupTotal;
                                                            @endphp --}}
                                                            <tr>
                                                                <td>
                                                                    <strong>
                                                                        <a data-toggle="collapse" href="#nonOperatingItemsAccount{{ $subgroup->id }}" role="button">
                                                                            <i class="fa fa-chevron-down collapse-icon"></i> {{ $subgroup->subgroup_name }}
                                                                        </a>
                                                                    </strong>
                                                                </td>
                                                                <td><strong>{{ bdt() }} {{ number_format($totalNonOperating, 2) }}</strong></td>
                                                            </tr>
                                                            <tbody class="collapse" id="nonOperatingItemsAccount{{ $subgroup->id }}">
                                                                @foreach($subgroup->ledgers as $ledger)
                                                                    @php
                                                                        $balance = abs(($ledger->total_debit ?? 0) - ($ledger->total_credit ?? 0));
                                                                    @endphp
                                                                    <tr>
                                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                                                        <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        @endforeach
                                                        
                                                        {{-- <tr>
                                                            <td><strong>Total Non Operating Items</strong></td>
                                                            <td><strong>{{ bdt() }} {{ number_format($totalNonOperating, 2) }}</strong></td>
                                                        </tr> --}}

                                                        <!-- Net Income -->
                                                        <tr>
                                                            <!-- <td><strong>Net Income (Operating Income - Non-Operating Items)</strong></td> -->
                                                            <td><strong>Net Income (Profit/Loss)</strong></td>
                                                            <td>
                                                                <strong>{{ bdt() }} {{ number_format($netIncome, 2) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div style="margin-top: 10px;">
                                                    <strong>Amount in Words:</strong>
                                                    <strong><em>{{ convertNumberToWords($netIncome) }}</em></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('js')
<!-- JavaScript for Printing -->
<script>
    function printProfitLoss() {
        var printContent = document.getElementById("printable-area").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
@endpush
