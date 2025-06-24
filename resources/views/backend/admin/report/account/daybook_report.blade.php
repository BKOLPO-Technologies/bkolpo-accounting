@extends('layouts.admin', ['pageTitle' => 'Report List'])
@section('admin')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Optional: hide the print button itself when printing */
            #printableArea .btn {
                display: none !important;
            }
        }
    </style>
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
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                            <div class="card-header py-2 text-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'Day Book Report' }}</h4>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::now()->format('F d, Y') }}
                                </small>
                            </div>

                            <div class="card-body">
                                <div id="printableArea">
                                    <div class="d-flex justify-content-end mb-3">
                                        <button class="btn btn-primary" onclick="window.print()">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example10" border="1" class="table-striped table-bordered"
                                            cellpadding="5" cellspacing="0" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Account</th>
                                                    <th>Debit</th>
                                                    {{-- <th>Credit</th> --}}
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $running_balance = 0;
                                                $totalDebit = 0;
                                                $totalCredit = 0;
                                            @endphp

                                            <tbody>
                                                @forelse($transactions as $txn)
                                                    @foreach ($txn->details as $detail)
                                                        @php
                                                            $totalDebit += $detail->debit;
                                                            $totalCredit += $detail->credit;
                                                            $running_balance += $detail->debit - $detail->credit;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ strtolower(\Carbon\Carbon::parse($txn->transaction_date)->format('d M Y')) }}</td>
                                                            <td>{{ $detail->description ?? '-' }}</td>
                                                            <td>{{ $detail->ledger->name ?? '-' }}</td>
                                                            <td>{{ number_format($detail->debit, 2) }}</td>
                                                            {{-- <td>{{ number_format($detail->credit, 2) }}</td> --}}
                                                            <td>{{ number_format($running_balance, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">No data found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end">Total</th>
                                                    <th>{{ number_format($totalDebit, 2) }}</th>
                                                    {{-- <th>{{ number_format($totalCredit, 2) }}</th> --}}
                                                    <th>{{ number_format($totalDebit, 2) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <!-- Amount in Words: Bottom Left with margin -->
                                        {{-- <div style="margin-top: 10px;">
                                            <strong>Amount in Words:</strong>
                                            <strong
                                                class="text-uppercase">{{ convertNumberToWords($running_balance) }}</strong>
                                        </div> --}}
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
