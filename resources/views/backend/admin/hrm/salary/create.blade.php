@extends('layouts.admin', [$pageTitle => 'Staff Salary Create'])

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
                                    <a href="{{ route('admin.staff.salary.index') }}"
                                        class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-money-check-alt me-2"></i> Staff Salary Generate
                                    </h5>
                                </div>

                                <form action="{{ route('admin.staff.salary.store') }}" method="POST">
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
                                                        <th>Basic</th>
                                                        <th>HRA</th>
                                                        <th>Medical</th>
                                                        <th>Conveyance</th>
                                                        <th>PF</th>
                                                        <th>Tax</th>
                                                        <th>Other Deduction</th>
                                                        <th>Gross</th>
                                                        <th>Net</th>
                                                        <th>Payment Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($staffs as $key => $staff)
                                                        <tr>
                                                            <td class="fw-bold text-start">{{ $staff->name }}</td>
                                                            <input type="hidden" name="staff_id[]"
                                                                value="{{ $staff->id }}">

                                                            <td><input type="number" min="0" step="any"
                                                                    name="basic_salary[]"
                                                                    class="form-control text-end basic_salary"
                                                                    value="{{ old('basic_salary.' . $key, $staff->salary ?? 0) }}">
                                                            </td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="hra[]" class="form-control text-end hra"
                                                                    value="{{ old('hra.' . $key, 0) }}"></td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="medical[]" class="form-control text-end medical"
                                                                    value="{{ old('medical.' . $key, 0) }}"></td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="conveyance[]"
                                                                    class="form-control text-end conveyance"
                                                                    value="{{ old('conveyance.' . $key, 0) }}"></td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="pf[]" class="form-control text-end pf"
                                                                    value="{{ old('pf.' . $key, 0) }}"></td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="tax[]" class="form-control text-end tax"
                                                                    value="{{ old('tax.' . $key, 0) }}"></td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="other_deductions[]"
                                                                    class="form-control text-end other_deductions"
                                                                    value="{{ old('other_deductions.' . $key, 0) }}"></td>

                                                            <td><input type="number" readonly
                                                                    class="form-control text-end bg-light gross_salary"
                                                                    value="{{ old('gross_salary.' . $key, $staff->salary ?? 0) }}">
                                                            </td>

                                                            <td><input type="number" readonly
                                                                    class="form-control text-end bg-light net_salary"
                                                                    value="{{ old('net_salary.' . $key, $staff->salary ?? 0) }}">
                                                            </td>

                                                            <td><input type="number" min="0" step="any"
                                                                    name="payment_amount[]"
                                                                    class="form-control text-end payment_amount"
                                                                    value="{{ old('payment_amount.' . $key, 0) }}"></td>
                                                            <!-- New -->
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- Payment Mode --}}
                                        <div class="row justify-content-center mt-4">
                                            <!-- Payment Method -->
                                            <div class="col-md-6 mb-3">
                                                <label for="ledger_group_id" class="form-label">Select Payment
                                                    Method:</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                    <select class="form-control" name="payment_method"
                                                        id="payment_method" required>
                                                        <option value="">Choose Payment Method</option>
                                                        @foreach ($ledgers as $ledger)
                                                            <option value="{{ $ledger->id }}"
                                                                data-type="{{ $ledger->type }}">{{ $ledger->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-success w-50 shadow-sm">
                                            <i class="fas fa-paper-plane"></i> Create Salary
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

            // Dynamic gross and net salary calculation
            $('tbody tr').each(function() {
                const row = $(this);

                function calculate() {
                    let basic = parseFloat(row.find('.basic_salary').val()) || 0;
                    let hra = parseFloat(row.find('.hra').val()) || 0;
                    let medical = parseFloat(row.find('.medical').val()) || 0;
                    let conveyance = parseFloat(row.find('.conveyance').val()) || 0;
                    let pf = parseFloat(row.find('.pf').val()) || 0;
                    let tax = parseFloat(row.find('.tax').val()) || 0;
                    let other = parseFloat(row.find('.other_deductions').val()) || 0;

                    let gross = basic + hra + medical + conveyance;
                    let net = gross - (pf + tax + other);

                    row.find('.gross_salary').val(gross.toFixed(2));
                    row.find('.net_salary').val(net.toFixed(2));
                }

                row.find('input').on('input', calculate);
            });
        });
    </script>
@endpush
