@extends('layouts.admin', [$pageTitle => 'Staff Salary Payment Details'])

@section('admin')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
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
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('admin.staff.salary.payment.index') }}"
                                        class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Staff Name</th>
                                            <td>{{ $salary->staff->name ?? '-' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $salary->staff->department ?? '-' }}</td>
                                        </tr>

                                        <tr>
                                            <th>Salary Month</th>
                                            <td>{{ \Carbon\Carbon::parse($salary->salary_month)->format('F, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Gross Salary</th>
                                            <td class="font-weight-bolder text-success">
                                                {{ bdt() }} {{ number_format($salary->gross, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Net Salary</th>
                                            <td class="font-weight-bolder text-primary">
                                                {{ bdt() }} {{ number_format($salary->net, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Payment Amount</th>
                                            <td class="font-weight-bolder text-success">
                                                {{ bdt() }} {{ number_format($salary->payment_amount, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Due Amount</th>
                                            <td
                                                class="font-weight-bolder {{ $salary->net - $salary->payment_amount > 0 ? 'text-danger' : 'text-muted' }}">
                                                {{ bdt() }}
                                                {{ number_format($salary->net - $salary->payment_amount, 2) }}
                                            </td>
                                        </tr>


                                        <tr>
                                            <th>Payment Status</th>
                                            <td>
                                                @switch($salary->status)
                                                    @case('approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @break

                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @break

                                                    @case('unpaid')
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    @break

                                                    @case('partial_paid')
                                                        <span class="badge bg-info text-dark">Partial Paid</span>
                                                    @break

                                                    @case('paid')
                                                        <span class="badge bg-primary">Paid</span>
                                                    @break

                                                    @case('hold')
                                                        <span class="badge bg-dark">Hold</span>
                                                    @break

                                                    @case('rejected')
                                                        <span class="badge bg-secondary">Rejected</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-light text-dark">Unknown</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Date</th>
                                            <td>{{ \Carbon\Carbon::parse($salary->payment_date)->format('d F Y') }}</td>
                                        </tr>

                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $salary->created_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
