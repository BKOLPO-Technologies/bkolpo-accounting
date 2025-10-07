@extends('layouts.admin', ['pageTitle' => 'Salary Payment List'])

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
                                    <a href="{{ route('admin.staff.salary.payment.create') }}"
                                        class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add Salary Payment
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <form action="{{ route('admin.staff.salary.payment.index') }}" method="GET">
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
                                                class="btn btn-danger">Clear</a>
                                        </div>
                                        {{-- Print Button --}}
                                        <div class="col-md-4 text-right">
                                            <button type="button" class="btn btn-info shadow-sm"
                                                onclick="togglePrintView()">
                                                <i class="fas fa-print me-1"></i> Print Salary Payment List
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Normal View -->
                                <div id="normalView">
                                    <!-- Salary Table -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-success text-center">
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Photo</th>
                                                    <th>Name</th>
                                                    <th>Department</th>
                                                    <th>Month</th>
                                                    <th>Gross</th>
                                                    <th>Net</th>
                                                    <th>Due</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                                @forelse ($salaries as $key => $salary)
                                                    <tr>
                                                        <td>{{ $salaries->firstItem() + $key }}</td>
                                                        <td>
                                                            <img src="{{ !empty($salary->staff->profile_image) ? url($salary->staff->profile_image) : 'https://via.placeholder.com/70x60' }}"
                                                                class="rounded-circle" style="width:50px;height:50px;">
                                                        </td>
                                                        <td>{{ $salary->staff->name ?? '-' }}</td>
                                                        <td>{{ $salary->staff->department ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}
                                                        </td>
                                                        <td>{{ bdt() }} {{ number_format($salary->gross, 2) }}
                                                        </td>
                                                        <td>{{ bdt() }} {{ number_format($salary->net, 2) }}</td>
                                                        <td>{{ bdt() }}
                                                            {{ number_format($salary->net - $salary->payment_amount, 2) }}
                                                        </td>
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
                                                        <td>
                                                            <a href="{{ route('admin.staff.salary.payment.show', $salary->id) }}"
                                                                class="btn btn-success btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button
                                                                class="btn btn-primary btn-sm {{ $salary->status === 'paid' ? 'disabled' : '' }}"
                                                                data-toggle="modal"
                                                                data-target="#paymentModal{{ $salary->id }}"
                                                                style="{{ $salary->status === 'paid' ? 'pointer-events: none; cursor: not-allowed;' : '' }}">
                                                                <i class="fas fa-dollar-sign"></i> Pay
                                                            </button>
                                                            <a href="{{ route('admin.staff.salary.payment.delete', $salary->id) }}"
                                                                id="delete" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <!-- Payment Modal -->
                                                    <div class="modal fade" id="paymentModal{{ $salary->id }}"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('admin.staff.salary.payment.pay') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="salary_id"
                                                                        value="{{ $salary->id }}">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Salary Payment
                                                                            ({{ $salary->staff->name ?? '' }})
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="salary_id"
                                                                            value="{{ $salary->id }}">
                                                                        <div class="card shadow-lg mb-4 mx-auto"
                                                                            style="max-width: 400px;">
                                                                            <div class="card-body">
                                                                                <ul class="list-group list-group-flush">
                                                                                    <li
                                                                                        class="list-group-item d-flex justify-content-between">
                                                                                        <strong>Net Salary:</strong>
                                                                                        <span>{{ bdt() }}
                                                                                            {{ number_format($salary->net, 2) }}</span>
                                                                                    </li>
                                                                                    <li
                                                                                        class="list-group-item d-flex justify-content-between">
                                                                                        <strong>Paid:</strong>
                                                                                        <span
                                                                                            class="text-success">{{ bdt() }}
                                                                                            {{ number_format($salary->payment_amount ?? 0, 2) }}</span>
                                                                                    </li>
                                                                                    <li
                                                                                        class="list-group-item d-flex justify-content-between">
                                                                                        <strong>Due:</strong>
                                                                                        <span class="text-danger">
                                                                                            {{ bdt() }}
                                                                                            {{ number_format(($salary->net ?? 0) - ($salary->payment_amount ?? 0), 2) }}
                                                                                        </span>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Payment Amount -->
                                                                        <div class="mb-3">
                                                                            <label for="payment_amount"
                                                                                class="form-label">Payment
                                                                                Amount
                                                                            </label>
                                                                            <input type="number" class="form-control"
                                                                                id="paymentAmountInput{{ $salary->id }}"
                                                                                name="payment_amount"
                                                                                max="{{ ($salary->net ?? 0) - ($salary->payment_amount ?? 0) }}"
                                                                                value="{{ old('payment_amount', ($salary->net ?? 0) - ($salary->payment_amount ?? 0)) }}"
                                                                                min="0"
                                                                                placeholder="Enter Payment Amount"
                                                                                required>

                                                                        </div>

                                                                        <!-- Payment Method -->
                                                                        <div class="mb-3">
                                                                            <label for="payment_method"
                                                                                class="form-label">Select
                                                                                Payment Method</label>
                                                                            <select class="form-control"
                                                                                name="payment_method" id="payment_method"
                                                                                required>
                                                                                <option value="">Choose Payment
                                                                                    Method
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
                                                                        <button type="submit"
                                                                            class="btn btn-success">Save
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
                                                            <td colspan="10" class="text-center">No records found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-end mt-3">
                                            {{ $salaries->links('pagination::bootstrap-5') }}
                                        </div>
                                    </div>

                                    <!-- Print View (Hidden by Default) -->
                                    <div id="printView" style="display: none;">
                                        <div class="d-flex justify-content-between mb-3">
                                            <button type="button" class="btn btn-secondary" onclick="togglePrintView()">
                                                <i class="fas fa-arrow-left me-1"></i> Back to Normal View
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="printSalaryList()">
                                                <i class="fas fa-print me-1"></i> Print Now
                                            </button>
                                        </div>

                                        <div class="print-container">
                                            <!-- Print Header -->
                                            <div class="print-header text-center mb-4 p-3 border-bottom">
                                                <h2 class="mb-1">Staff Salary Generate List</h2>
                                                <p class="mb-1 text-light">
                                                    Period:
                                                    @if (request()->month && request()->year)
                                                        {{ \Carbon\Carbon::create()->month(request()->month)->format('F') }}
                                                        {{ request()->year }}
                                                    @else
                                                        All Time
                                                    @endif
                                                </p>
                                                <p class="mb-0 text-light">
                                                    Generated on: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}
                                                </p>
                                            </div>

                                            <!-- Print Table -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Name</th>
                                                            <th>Department</th>
                                                            <th>Month</th>
                                                            <th>Gross Salary</th>
                                                            <th>Net Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($salaries as $key => $salary)
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td>{{ $salary->staff->name ?? '-' }}</td>
                                                                <td>{{ $salary->staff->department ?? '-' }}</td>
                                                                <td class="text-left">
                                                                    {{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}
                                                                </td>
                                                                <td class="text-left">{{ bdt() }}
                                                                    {{ number_format($salary->gross, 2) }}</td>
                                                                <td class="text-left">{{ bdt() }}
                                                                    {{ number_format($salary->net, 2) }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6" class="text-center">No records found.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    @if ($salaries->count() > 0)
                                                        <tfoot>
                                                            <tr class="table-info">
                                                                <td colspan="4" class="text-right"><strong>Total:</strong>
                                                                </td>
                                                                <td class="text-left"><strong>{{ bdt() }}
                                                                        {{ number_format($salaries->sum('gross'), 2) }}</strong>
                                                                </td>
                                                                <td class="text-left"><strong>{{ bdt() }}
                                                                        {{ number_format($salaries->sum('net'), 2) }}</strong>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    @endif
                                                </table>
                                            </div>

                                            <!-- Print Footer -->
                                            <div class="print-footer mt-4 p-3 border-top">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-1"><strong>Total Records:</strong>
                                                            {{ $salaries->count() }}</p>
                                                        <p class="mb-0"><strong>Printed by:</strong>
                                                            {{ Auth::user()->name ?? 'Admin' }}</p>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <p class="mb-0"><strong>Print Date:</strong>
                                                            {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</p>
                                                    </div>
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

    @push('css')
        <style>
            /* Print-specific styles */
            @media print {
                body * {
                    visibility: hidden;
                }

                #printView,
                #printView * {
                    visibility: visible;
                }

                #printView {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    background: white;
                }

                .print-header {
                    background: white !important;
                    color: black !important;
                }

                .table-bordered th,
                .table-bordered td {
                    border-color: #000 !important;
                }

                .btn {
                    display: none !important;
                }
            }

            /* Print view styles */
            .print-container {
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .print-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .print-header h2 {
                margin: 0;
                font-weight: 600;
            }

            .print-footer {
                background: #f8f9fa;
                border-radius: 5px;
                font-size: 14px;
            }
        </style>
    @endpush

    @push('js')
        <script>
            $(document).ready(function() {
                $('form').on('submit', function() {
                    $('button[type="submit"]').text('Reloading...').prop('disabled', true);
                });
            });

            function togglePrintView() {
                const normalView = document.getElementById('normalView');
                const printView = document.getElementById('printView');

                if (normalView.style.display !== 'none') {
                    // Switch to print view
                    normalView.style.display = 'none';
                    printView.style.display = 'block';
                    // Scroll to top
                    window.scrollTo(0, 0);
                } else {
                    // Switch back to normal view
                    normalView.style.display = 'block';
                    printView.style.display = 'none';
                }
            }

            function printSalaryList() {
                window.print();
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Loop through each modal if there are multiple
                @foreach ($salaries as $salary)
                    const input{{ $salary->id }} = document.getElementById('paymentAmountInput{{ $salary->id }}');
                    const max{{ $salary->id }} = parseFloat(input{{ $salary->id }}.max);

                    input{{ $salary->id }}.addEventListener('input', function() {
                        const enteredValue = parseFloat(this.value);

                        if (enteredValue > max{{ $salary->id }}) {
                            this.classList.add('is-invalid');

                            // Toastr error
                            toastr.error(
                                'Payment amount cannot exceed due amount ({{ number_format(($salary->net ?? 0) - ($salary->payment_amount ?? 0), 2) }}).'
                            );
                        } else {
                            this.classList.remove('is-invalid');
                        }
                    });
                @endforeach
            });
        </script>
    @endpush
