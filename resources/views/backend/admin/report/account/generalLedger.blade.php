@extends('layouts.admin', ['pageTitle' => 'Journal Voucher List'])
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
                        <div class="card-body" style="display: flex; justify-content: center; align-items: center; height: 100%;">

                            <!-- --- -->
                            <!-- in here  -->

                            <!-- Centered Content with Border -->
                            <div style="width: 50%; border: 1px solid #ddd; padding: 20px; border-radius: 8px; background-color: #f9f9f9;">

                            <!-- Heading -->
                            <div class="text-center mb-4">
                                <h4 style="margin: 0; font-weight: bold;">Populous United</h4>
                                <p style="margin: 0; font-weight: bold;">General Ledger</p>
                                <p style="margin: 0;">01-01-2025 - 29-01-2025</p>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction Type</th>
                                            <th>Customer/Description/Split</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledgerData as $section)
                                            <tr>
                                                <td colspan="5"><strong>{{ $section['section'] }}</strong></td>
                                            </tr>
                                            @foreach ($section['entries'] as $entry)
                                                <tr>
                                                    <td>{{ $entry['date'] }}</td>
                                                    <td>{{ $entry['type'] }}</td>
                                                    <td>{{ $entry['description'] }}</td>
                                                    <td>${{ number_format($entry['amount'], 2) }}</td>
                                                    <td>${{ number_format($entry['balance'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3"><strong>Total for {{ $section['section'] }}</strong></td>
                                                <td colspan="2"><strong>${{ number_format($section['total'], 2) }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            </div>

                            <!-- --- -->

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