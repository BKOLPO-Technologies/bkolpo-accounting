@extends('layouts.admin', ['pageTitle' => 'Salary List'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('admin.staff.salary.create') }}"
                                        class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add Salary
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <form action="{{ route('admin.staff.salary.index') }}" method="GET">
                                    <div class="row mb-3 justify-content-center">
                                        <div class="col-md-3">
                                            <select name="month" class="form-control" required>
                                                <option value="">Select Month</option>
                                                @foreach (range(1, 12) as $month)
                                                    <option value="{{ $month }}"
                                                        {{ request()->month == $month ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="year" class="form-control" required>
                                                <option value="">Select Year</option>
                                                @foreach (range(2020, date('Y')) as $year)
                                                    <option value="{{ $year }}"
                                                        {{ request()->year == $year ? 'selected' : '' }}>
                                                        {{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('admin.staff.salary.index') }}"
                                                class="btn btn-danger">Reload</a>
                                        </div>
                                    </div>
                                </form>

                                <!-- Salary Table -->
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Salary</th>
                                            <th>Payment</th>
                                            <th>Due</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($salaries as $key => $salary)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <img src="{{ !empty($salary->staff->profile_image) ? url($salary->staff->profile_image) : url('https://via.placeholder.com/70x60') }}"
                                                        class="staff-profile-image-small" alt="Profile Image"
                                                        style="width: 50px; height: 50px; border-radius: 50%;">
                                                </td>
                                                <td>{{ $salary->staff->name ?? '' }}</td>

                                                <!-- Salary / Payment / Due -->
                                                <td class="font-weight-bolder">
                                                    {{ bdt() }} {{ number_format($salary->net_salary, 2) }}
                                                </td>
                                                <td class="font-weight-bolder">
                                                    {{ bdt() }}
                                                    {{ number_format($salary->payment_amount ?? 0, 2) }}
                                                </td>
                                                <td class="font-weight-bolder">
                                                    {{ bdt() }}
                                                    {{ number_format($salary->net_salary - ($salary->payment_amount ?? 0), 2) }}
                                                </td>

                                                <td>
                                                    @if ($salary->status == 'Paid')
                                                        <span class="p-1 bg-success text-white rounded">Paid</span>
                                                    @elseif ($salary->status == 'partial_paid')
                                                        <span class="p-1 bg-warning text-white rounded">Partially
                                                            Paid</span>
                                                    @elseif ($salary->status == 'Unpaid')
                                                        <span class="p-1 bg-danger text-white rounded">Not Paid</span>
                                                    @else
                                                        <span class="p-1 bg-secondary text-white rounded">Undefined</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.staff.salary.show', $salary->id) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                        data-target="#paymentModal{{ $salary->id }}">
                                                        <i class="fas fa-dollar-sign"></i> Pay
                                                    </button>
                                                    <a href="{{ route('admin.staff.salary.delete', $salary->id) }}"
                                                        id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Payment Modal -->
                                            <div class="modal fade" id="paymentModal{{ $salary->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.staff.salary.pay') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="salary_id"
                                                                value="{{ $salary->id }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Salary Payment
                                                                    ({{ $salary->staff->name ?? '' }})
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="salary_id"
                                                                    value="{{ $salary->id }}">
                                                                <p><b>Salary:</b>
                                                                    {{ number_format($salary->net_salary, 2) }}</p>
                                                                <p><b>Paid:</b>
                                                                    {{ number_format($salary->payment_amount ?? 0, 2) }}
                                                                </p>
                                                                <p><b>Due:</b>
                                                                    {{ number_format(($salary->net_salary ?? 0) - ($salary->payment_amount ?? 0), 2) }}
                                                                </p>


                                                                <!-- Payment Amount -->
                                                                <div class="mb-3">
                                                                    <label for="payment_amount" class="form-label">Payment
                                                                        Amount</label>
                                                                    <input type="number" class="form-control"
                                                                        name="payment_amount"
                                                                        max="{{ ($salary->net_salary ?? 0) - ($salary->payment_amount ?? 0) }}"
                                                                        value="{{ ($salary->net_salary ?? 0) - ($salary->payment_amount ?? 0) }}"
                                                                        required>
                                                                </div>

                                                                <!-- Payment Method -->
                                                                <div class="mb-3">
                                                                    <label for="payment_method" class="form-label">Select
                                                                        Payment Method</label>
                                                                    <select class="form-control" name="payment_method"
                                                                        id="payment_method" required>
                                                                        <option value="">Choose Payment Method
                                                                        </option>
                                                                        @foreach ($ledgers as $ledger)
                                                                            <option value="{{ $ledger->id }}"
                                                                                data-type="{{ $ledger->type }}"
                                                                                {{ $salary->ledger_id == $ledger->id ? 'selected' : '' }}>
                                                                                {{ $ledger->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Save
                                                                    Payment</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No records found.</td>
                                            </tr>
                                        @endforelse
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
        $(document).ready(function() {
            $('form').on('submit', function() {
                // Change button text to "Reloading..." when form is being submitted
                $('button[type="submit"]').text('Reloading...').prop('disabled', true);
            });
        });
    </script>
@endpush
