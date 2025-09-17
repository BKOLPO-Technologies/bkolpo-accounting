@extends('layouts.admin', ['pageTitle' => 'Receive Payment List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
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
                                    <a href="{{ route('project.advance.receipt.payment.index') }}"
                                        class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Project Name</th>
                                            <td>{{ $project->project_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>{{ $project->project_location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Coordinator</th>
                                            <td>{{ $project->project_coordinator }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer</th>
                                            <td>{{ $project->client->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Reference No</th>
                                            <td>{{ $project->reference_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Schedule Date</th>
                                            <td>{{ $project->schedule_date ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Project Type</th>
                                            <td>{{ ucfirst($project->project_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $project->description ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terms & Conditions</th>
                                            <td>{!! $project->terms_conditions ?? '' !!}</td>
                                        </tr>
                                    </table>
                                </div>
                                <h4>Receive Amount History</h4>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Receive Date</th>
                                            <th>Payment Method</th>
                                            <th>Ledger</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalReceived = 0;
                                        @endphp

                                        @forelse ($project->advancereceipts as $key => $r)
                                            @php
                                                $totalReceived += $r->receive_amount;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $r->payment_date }}</td>
                                                <td>{{ ucfirst($r->payment_method) }}</td>
                                                <td>{{ $r->ledger->name ?? '' }}</td>
                                                <td>{{ number_format($r->receive_amount, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No payments received yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                    @if ($project->advancereceipts->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right">Total Received</th>
                                                <th class="text-left" colspan="">
                                                <b>{{ number_format($totalReceived, 2) }}</b></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                                <!-- Amount in Words -->
                                    <div class="mt-3">
                                        <strong>Amount in Words:</strong>
                                        <strong>{{ convertNumberToWords($totalReceived) }}</strong>
                                    </div>
                                </div>
                            <div>
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
            $('.select2').select2();
        });
    </script>
@endpush
