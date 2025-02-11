@extends('layouts.admin', ['pageTitle' => 'Balance Sheet Report'])

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
                        <div class="card-body">
                            <!-- Trial Balance Table -->
                            <div class="card-header text-center mb-3">
                                <h2 class="mb-1">{{ config('app.name') }}</h2> 
                                <p class="mb-0"><strong>Balance Sheet Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div clas="card-body">
                                <div class="row mb-5">
                                    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                        <!-- Balance Shit Table -->
                                        @foreach ($ledgerGroups as $group)
                                            <h2>{{ $group->group_name ?? 'N/A' }}</h2>
                                            <div class="table-responsive">
                                                <table id="example10" border="1" cellpadding="5" cellspacing="0" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 85%;"></th>
                                                            <th style="width: 15%;">Amount (৳)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $groupTotalDebit = 0;
                                                            $groupTotalCredit = 0;
                                                        @endphp
                                                        @foreach ($group->ledgers as $ledger)
                                                            @php
                                                                $groupTotalDebit += $ledger->total_debit;
                                                                $groupTotalCredit += $ledger->total_credit;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $ledger->name }}</td>
                                                                <td>৳{{ number_format($ledger->total_debit, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Total</th>
                                                            <th>৳{{ number_format($groupTotalDebit, 2) }}</th>
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
    </section>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('.table').DataTable();
});
</script>
@endpush
