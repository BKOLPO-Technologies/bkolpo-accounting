@extends('layouts.admin', ['pageTitle' => 'Profit & Loss Report'])
<!-- CSS to Hide Form on Print -->
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
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
                                <a href="{{ route('report.balance.sheet')}}" class="btn btn-sm btn-danger rounded-0">
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
                                <!-- Balance Sheet Table -->
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">{{ config('app.name') }}</h2> 
                                    <p class="mb-0"><strong>Profit & Loss Report</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <div class="card-body">
                                    <!-- Date Filter Form -->
                                    <div id="filter-form">
                                        <form action="{{ route('report.ledger.profit.loss') }}" method="GET" class="mb-3">
                                            <div class="row justify-content-center">
                                                <div class="col-md-3 mt-3">
                                                    <label for="from_date">From Date:</label>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                                </div>
                                                <div class="col-md-3 mt-3">
                                                    <label for="to_date">To Date:</label>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                                </div>
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                                </div>
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <a href="{{ route('report.ledger.profit.loss') }}"  class="btn btn-danger w-100">Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                            <!-- Profit & Loss Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Ledger Account</th>
                                                            <th>Debit (Expenses)</th>
                                                            <th>Credit (Income)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($profitLossData as $ledgerName => $entries)
                                                            <tr>
                                                                <td><strong>{{ $ledgerName }}</strong></td>
                                                                <td class="text-right">
                                                                    ৳{{ number_format($entries->sum('debit'), 2) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    ৳{{ number_format($entries->sum('credit'), 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="table-primary">
                                                            <th>Total</th>
                                                            <th class="text-right">৳{{ number_format($totalDebit, 2) }}</th>
                                                            <th class="text-right">৳{{ number_format($totalCredit, 2) }}</th>
                                                        </tr>
                                                        <tr class="table-success">
                                                            <th>Net Profit / Loss</th>
                                                            <th colspan="2" class="text-right">
                                                                ৳{{ number_format($netProfitLoss, 2) }}
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
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
