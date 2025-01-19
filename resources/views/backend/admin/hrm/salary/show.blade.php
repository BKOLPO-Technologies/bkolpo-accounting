@extends('layouts.admin', [$pageTitle => 'Salary Details'])

@section('admin')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('salary.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Staff Name</th>
                                        <td>{{ $salary->staff->name ?? 'N/A' }}</td>
                                    </tr>  
                                    <tr>
                                        <th>Salary</th>
                                        <td>৳{{ number_format($salary->salary, 2) ?? 'N/A' }}</td>
                                    </tr>  
                                    <!-- <tr>
                                        <th>Basic Salary</th>
                                        <td>৳{{ number_format($salary->basic_salary, 2) ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>House Rent</th>
                                        <td>৳{{ number_format($salary->house_rent, 2) ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Gross Salary</th>
                                        <td>৳{{ number_format($salary->gross_salary, 2) ?? 'N/A' }}</td>
                                    </tr> -->
                                    <tr>
                                        <th>Payment Amount</th>
                                        <td>৳{{ number_format($salary->payment_amount, 2) ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Due Amount</th>
                                        <td>৳{{ number_format($salary->will_get, 2) ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Mode</th>
                                        <td>{{ $salary->payment_mode ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($salary->status == 'Paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif ($salary->status == 'Partially Paid')
                                                <span class="badge badge-warning">Partially Paid</span>
                                            @else
                                                <span class="badge badge-danger">Not Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F d, Y') ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Month</th>
                                        <td>{{ \Carbon\Carbon::createFromFormat('m', $salary->month)->format('F') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Year</th>
                                        <td>{{ $salary->year ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Profile Image</th>
                                        <td>
                                            @if($salary->staff->profile_image)
                                                <img src="{{ url('upload/staff/'.$salary->staff->profile_image) }}" alt="Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <img src="https://via.placeholder.com/70x60" alt="No Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                            @endif
                                        </td>
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

@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
