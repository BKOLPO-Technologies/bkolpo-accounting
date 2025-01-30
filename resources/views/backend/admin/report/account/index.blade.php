@extends('layouts.admin', ['pageTitle' => 'Report List'])
@section('admin')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card shadow">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Bookkeeping</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                        <a href="{{ route('report.trial.balance') }}" class="fw-bold text-primary">Trial Balance</a>
                                        <p class="mb-0">This report summarises the debit and credit balances of each account on your chart of accounts during a period of time.</p>
                                        </div>
                                        <div class="col-lg-6">
                                        <a href="#" class="fw-bold text-primary">Balance Sheet</a>
                                        <p class="mb-0">What you own (assets), what you owe (liabilities), and what you invested (equity) compared to last year.</p>
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
// Initialize Select2 if necessary
$(document).ready(function() {
    $('.select2').select2();
});
</script>
@endpush