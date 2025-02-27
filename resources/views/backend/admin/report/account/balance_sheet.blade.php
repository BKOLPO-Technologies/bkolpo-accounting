@extends('layouts.admin', ['pageTitle' => 'Balance Sheet Report'])
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
                            <button class="btn btn-primary" onclick="printBalanceSheet()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>

                        <div id="printable-area">
                            <div class="card-body">
                                <!-- Balance Sheet Table -->
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">{{ config('app.name') }}</h2> 
                                    <p class="mb-0"><strong>Balance Sheet Report</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <div class="card-body">
                                    <!-- Date Filter Form -->
                                    <div id="filter-form">
                                        <form action="{{ route('report.balance.sheet') }}" method="GET" class="mb-3">
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
                                                    <a href="{{ route('report.balance.sheet') }}"  class="btn btn-danger w-100">Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                            <!-- Balance Sheet Table -->
                                            @foreach ($ledgerGroups as $group)
                                                <h2>{{ $group->group_name ?? 'N/A' }}</h2>
                                                <div class="table-responsive">
                                                    <table id="example10" border="1" class="table-striped table-bordered" cellpadding="5" cellspacing="0" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 85%;"></th>
                                                                <th style="width: 15%;">Amount ({{ bdt() }})</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $totalBalance = 0;
                                                            @endphp
                                                            @foreach ($group->ledgers as $ledger)
                                                                @php
                                                                    // Calculate the balance
                                                                    $balance = $ledger->debit;

                                                                    if ($ledger->debit > 0) {
                                                                        $balance += $ledger->total_debit - $ledger->total_credit;
                                                                    } else {
                                                                        $balance += $ledger->total_credit - $ledger->total_debit;
                                                                    }

                                                                    // Remove the negative sign (make the balance positive)
                                                                    $balance = abs($balance); // This will convert any negative balance to positive

                                                                    // Add balance to total balance
                                                                    $totalBalance += $balance;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $ledger->name }}</td>
                                                                    <td>{{ bdt() }} {{ number_format($balance, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Total</th>
                                                                <th>{{ bdt() }} {{ number_format($totalBalance, 2) }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            @endforeach
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
    function printBalanceSheet() {
        var printContent = document.getElementById("printable-area").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
@endpush
