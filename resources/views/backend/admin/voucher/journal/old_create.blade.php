@extends('layouts.admin', ['pageTitle' => 'Journal Voucher Create'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('journal-voucher.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Transaction Code (Auto Generated) -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="transaction_code">Voucher No</label>
                                            <input type="text" id="transaction_code" name="transaction_code" class="form-control @error('transaction_code') is-invalid @enderror" value="{{ old('transaction_code', $transactionCode) }}" readonly />
                                            @error('transaction_code')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Company Select -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="company_id">Company</label>
                                            <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                                                <option value="">Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Branch Select -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="branch_id">Branch</label>
                                            <select name="branch_id" id="branch_id" class="form-control @error('branch_id') is-invalid @enderror">
                                                <option value="">Select Branch</option>
                                            </select>
                                            @error('branch_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Date Input -->
                                        <div class="col-lg-12 mb-3">
                                            <label for="transaction_date">Transaction Date</label>
                                            <input type="date" id="transaction_date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date') }}" />
                                            @error('transaction_date')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Description Input -->
                                        <div class="col-lg-12 mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Enter Description" rows="2">{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Debit and Credit Table with 10 Default Rows -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered mt-4" id="journal-table">
                                            <thead>
                                                <tr>
                                                    <th>Ledger</th>
                                                    <th>Description</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select name="ledger_id[]" class="form-control @error('ledger_id.*') is-invalid @enderror">
                                                            <option value="">Select Ledger</option>
                                                            @foreach($ledgers as $ledger)
                                                                <option value="{{ $ledger->id }}" {{ old('ledger_id.0') == $ledger->id ? 'selected' : '' }}>{{ $ledger->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('ledger_id.*')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <textarea name="ledger_description[]" class="form-control @error('ledger_description.*') is-invalid @enderror" cols="1" rows="1" placeholder="Enter Description">{{ old('ledger_description.0') }}</textarea>
                                                        @error('ledger_description.*')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="debit[]" class="form-control @error('debit.*') is-invalid @enderror" step="0.01" value="{{ old('debit.0', '0.00') }}" />
                                                        @error('debit.*')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="credit[]" class="form-control @error('credit.*') is-invalid @enderror" step="0.01" value="{{ old('credit.0', '0.00') }}" />
                                                        @error('credit.*')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                        </div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-success btn-sm add-row"><i class="fas fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Save Journal Voucher
                                            </button>
                                        </div>
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
        // Initialize Select2 for existing rows
        $('.select2').select2();

        $(document).on('click', '.add-row', function () {
            var newRow = `
                <tr>
                    <td>
                        <select name="ledger_id[]" class="form-control">
                            @foreach($ledgers as $ledger)
                                <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="col-3">
                        <textarea name="ledger_description[]" class="form-control" cols="1" rows="1" placeholder="Enter Description"></textarea>
                    </td>
                    <td>
                        <input type="number" name="debit[]" class="form-control" step="0.01" value="0.00" />
                    </td>
                    <td>
                        <input type="number" name="credit[]" class="form-control" step="0.01" value="0.00" />
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-minus"></i></button>
                    </td>
                </tr>
            `;

            // Append the new row to the table body
            $('#journal-table tbody').append(newRow);

            // Reinitialize Select2 for the new row
            $('.select2').select2();
        });

        // Remove row functionality
        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
        });
    });

    // company to branch show
    $(document).ready(function () {
        $('#company_id').on('change', function () {
            let companyId = $(this).val();
            let branchSelect = $('#branch_id');

            // Clear previous options
            branchSelect.empty();
            branchSelect.append('<option value="">Select Branch</option>');

            if (companyId) {
                $.ajax({
                    url: '/admin/journal-voucher/get-branches/' + companyId, // Backend route
                    type: 'GET',
                    success: function (response) {
                        console.log(response); // Debug: Check the response in the console
                        
                        // Clear existing options in the branch dropdown
                        branchSelect.empty();
                        branchSelect.append('<option value="">Select Branch</option>');

                        // Check if the response is successful
                        if (response.success) {
                            const branch = response.branch; // Single branch
                            branchSelect.append(
                                `<option value="${branch.id}">${branch.name}</option>`
                            );
                            toastr.success('Branch loaded successfully!');
                        } else {
                            toastr.warning(response.message || 'No branch found for this company.');
                        }
                    },
                    error: function () {
                        toastr.error('Error retrieving branches. Please try again.');
                    },
                });
            } else {
                // Clear branch dropdown if no company is selected
                branchSelect.empty();
                branchSelect.append('<option value="">Select Branch</option>');
                toastr.info('Please select a company to load branches.');
            }
        });
    });

</script>
@endpush
