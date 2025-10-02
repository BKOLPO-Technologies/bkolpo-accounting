@extends('layouts.admin', [$pageTitle => 'Salary Details'])

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
                                    <a href="{{ route('admin.staff.salary.index') }}"
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
                                            <th>Salary Month</th>
                                            <td>{{ \Carbon\Carbon::parse($salary->salary_month)->format('F, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Basic Salary</th>
                                            <td> {{ bdt() }} {{ number_format($salary->basic_salary, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>HRA</th>
                                            <td> {{ bdt() }} {{ number_format($salary->hra, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Medical</th>
                                            <td> {{ bdt() }} {{ number_format($salary->medical, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Conveyance</th>
                                            <td> {{ bdt() }} {{ number_format($salary->conveyance, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>PF</th>
                                            <td> {{ bdt() }} {{ number_format($salary->pf, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax</th>
                                            <td> {{ bdt() }} {{ number_format($salary->tax, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Other Deductions</th>
                                            <td> {{ bdt() }} {{ number_format($salary->other_deductions, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Gross Salary</th>
                                            <td> {{ bdt() }} {{ number_format($salary->gross_salary, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Net Salary</th>
                                            <td> {{ bdt() }} {{ number_format($salary->net_salary, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status</th>
                                            <td>
                                                @if ($salary->status == 'Paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($salary->status == 'partial_paid')
                                                    <span class="badge bg-warning">Partially Paid</span>
                                                @elseif($salary->status == 'Unpaid')
                                                    <span class="badge bg-danger">Not Paid</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $salary->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Method</th>
                                            <td>{{ ucfirst($salary->payment_method ?? '-') }}</td>
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
