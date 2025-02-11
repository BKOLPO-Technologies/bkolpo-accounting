@extends('layouts.admin', ['pageTitle' => 'Trial Balance Report'])

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
                                <a href="{{ route('report.trial.balance')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Date Filter Form -->
                            <form action="{{ route('report.trial.balance') }}" method="GET" class="mb-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date:</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date:</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Trial Balance Table -->
                            <div class="card-header text-center mb-3">
                                <h2 class="mb-1">{{ config('app.name') }}</h2> 
                                <p class="mb-0"><strong>Trial Balance Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div clas="card-body">
                                <div class="row ">
                                    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                        <!-- Trial Balance Table -->
                                        <div class="table-responsive">
                                            <table class="table-striped" style="border-collapse: collapse; width: 100%;">
                                                <thead style="border-bottom: 2px solid black;">
                                                    <tr>
                                                        <th>Ledger Name</th>
                                                        <th class="text-end">Debit (৳)</th>
                                                        <th class="text-end">Credit (৳)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($trialBalances as $key => $ledger)
                                                        <tr>
                                                            <td>{{ $ledger['ledger_name'] }}</td>
                                                            <td class="text-end">৳{{ number_format($ledger['debit'], 2) }}</td>
                                                            <td class="text-end">৳{{ number_format($ledger['credit'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot style="border-top: 2px solid black;">
                                                    <tr class="fw-bold">
                                                        <td colspan="1" class="font-weight-bolder text-right"></td>
                                                        <td class="text-end font-weight-bolder">৳{{ number_format($trialBalances->sum('debit'), 2) }}</td>
                                                        <td class="text-end font-weight-bolder">৳{{ number_format($trialBalances->sum('credit'), 2) }}</td>
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
