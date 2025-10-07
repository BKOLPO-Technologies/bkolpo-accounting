@extends('layouts.admin', [$pageTitle => 'Staff Salary Generate Create'])

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
                <div class="row">
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

                            <div class="card shadow-lg border-0">
                                <form action="{{ route('admin.staff.salary.payment.store') }}" method="POST">
                                    @csrf
                                    <div class="card-body">

                                        {{-- Month & Year Selection --}}
                                        <div class="row justify-content-center mb-4">
                                            <div class="col-md-4">
                                                <label for="month" class="form-label fw-bold">Salary Month</label>
                                                <select name="month" id="month" class="form-control select2" required>
                                                    @for ($m = 1; $m <= 12; $m++)
                                                        <option value="{{ $m }}"
                                                            {{ date('m') == $m ? 'selected' : '' }}>
                                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="year" class="form-label fw-bold">Salary Year</label>
                                                <select name="year" id="year" class="form-control select2" required>
                                                    @for ($y = date('Y') - 2; $y <= date('Y'); $y++)
                                                        <option value="{{ $y }}"
                                                            {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Staff Salary Table --}}
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover align-middle text-center">
                                                <thead class="table-success">
                                                    <tr>
                                                        <th>Staff Name</th>
                                                        <th>Gross</th>
                                                        <th>Net</th>
                                                        <th>Payment Amount</th>
                                                        <th>Due Amount</th>
                                                        <th>Payment Method</th>
                                                        <th>Note</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($staffs as $key => $staff)
                                                        <tr>
                                                            <td class="fw-bold text-start col-1">{{ $staff->name }}</td>
                                                            <input type="hidden" name="staff_id[]"
                                                                value="{{ $staff->id }}">



                                                            <td><input type="number" min="0" readonly
                                                                    class="form-control bg-warning-subtle gross_salary"
                                                                    value="{{ $staff->salaryStructure->gross ?? 0 }}"></td>

                                                            <td><input type="number" min="0" readonly
                                                                    class="form-control bg-warning-subtle net_salary"
                                                                    value="{{ $staff->salaryStructure->net ?? 0 }}"></td>

                                                            <td><input type="number" min="0" name="payment_amount[]"
                                                                    placeholder="Enter Payment Amount"
                                                                    class="form-control text-end  payment_amount"></td>
                                                            <td>
                                                                <input type="number" readonly
                                                                    class="form-control text-end bg-warning-subtle due_amount"
                                                                    value="{{ $staff->salaryStructure->net ?? 0 }}">
                                                            </td>

                                                            <td class="col-2">
                                                                <select name="payment_method[]" class="form-control">
                                                                    <option value="">Choose Method</option>
                                                                    @foreach ($ledgers as $ledger)
                                                                        <option value="{{ $ledger->id }}"
                                                                            data-type="{{ $ledger->type }}">
                                                                            {{ $ledger->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea name="note[]" rows="2" placeholder="Enter note (optional)" class="form-control text-start"></textarea>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-success w-50 shadow-sm">
                                            <i class="fas fa-paper-plane"></i> Create Salary Payment
                                        </button>
                                    </div>
                                </form>
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
    <script>
        $(document).on('input', '.basic_salary, .hra, .medical, .conveyance, .pf, .tax, .other_deductions', function() {
            const row = $(this).closest('tr');

            const basic = parseFloat(row.find('.basic_salary').val()) || 0;
            const hra = parseFloat(row.find('.hra').val()) || 0;
            const medical = parseFloat(row.find('.medical').val()) || 0;
            const conveyance = parseFloat(row.find('.conveyance').val()) || 0;
            const pf = parseFloat(row.find('.pf').val()) || 0;
            const tax = parseFloat(row.find('.tax').val()) || 0;
            const other = parseFloat(row.find('.other_deductions').val()) || 0;

            const gross = basic + hra + medical + conveyance;
            const net = gross - (pf + tax + other);

            row.find('.gross_salary').val(gross.toFixed(2));
            row.find('.net_salary').val(net.toFixed(2));
            row.find('.payment_amount').val(net.toFixed(2));
        });

        // Payment amount and due validation
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('payment_amount')) {
                const row = e.target.closest('tr');
                const net = parseFloat(row.querySelector('.net_salary').value) || 0;
                const pay = parseFloat(e.target.value) || 0;
                const due = Math.max(net - pay, 0);
                row.querySelector('.due_amount').value = due.toFixed(2);

                // Validation
                if (pay > net) {
                    // Show toastr error
                    toastr.error('Payment amount cannot exceed net salary!');
                    // Highlight border in red
                    e.target.style.border = '2px solid red';
                } else {
                    // Remove red border
                    e.target.style.border = '';
                }
            }
        });
    </script>
@endpush
