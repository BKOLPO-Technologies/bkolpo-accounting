@extends('layouts.admin', ['pageTitle' => 'Journal Voucher Create'])
@section('admin')
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
                                            <label for="transaction_date">Date</label>
                                            <input type="text" id="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" />
                                            @error('transaction_date')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row p-1">
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-secondary">
                                                <tbody>
                                                <table class="table table-bordered border-secondary">
                                                    <thead class="table-light">
                                                        <tr style="background:#dcdcdc; text-align:center;">
                                                            <th style="background:#dcdcdc;">Sl</th>
                                                            <th>Type</th>
                                                            <th>Group</th>
                                                            <th>Sub Group</th>
                                                            <th>Ledger</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Manually added 10 rows -->
                                                        @for ($i = 0; $i < 10; $i++)
                                                            <tr>
                                                                <td class="text-center">{{ $i + 1 }}</td>
                                                                <td style="width: 15% !important;">
                                                                    <div class="input-group">
                                                                        <select class="form-control" name="type[]">
                                                                            <option value="">Select Type</option>
                                                                            <option value="Asset">Asset</option>
                                                                            <option value="Liability">Liability</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="reference_no[]" 
                                                                        placeholder="Enter Group No" value="{{ old("reference_no.$i") }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="reference_no[]" 
                                                                        placeholder="Enter Sub-Group No" value="{{ old("reference_no.$i") }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="reference_no[]" 
                                                                        placeholder="Enter Ledger No" value="{{ old("reference_no.$i") }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control text-end debit" name="debit[]" 
                                                                        value="{{ old("debit.$i", '') }}" placeholder="Enter Debit Amount">
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control text-end credit" name="credit[]" 
                                                                        value="{{ old("credit.$i", '') }}" placeholder="Enter Credit Amount">
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" style="text-align: right; padding-right: 1rem;"><strong>Total:</strong></td>
                                                            <td style="text-align: right;"><strong id="debitTotal">৳0.00</strong></td>
                                                            <td style="text-align: right;"><strong id="creditTotal">৳0.00</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Post Journal Voucher
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
    $(document).ready(function () {
        function formatCurrency(amount) {
            return '৳' + new Intl.NumberFormat('en-BD', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(amount);
        }

        function calculateTotals() {
            let totalDebit = 0, totalCredit = 0;

            $(".debit").each(function () {
                totalDebit += parseFloat($(this).val()) || 0;
            });

            $(".credit").each(function () {
                totalCredit += parseFloat($(this).val()) || 0;
            });

            $("#debitTotal").text(formatCurrency(totalDebit));
            $("#creditTotal").text(formatCurrency(totalCredit));
        }

        $(document).on("keyup", ".debit, .credit", function () {
            calculateTotals();
        });

        // Call once to update if there are pre-filled values
        calculateTotals();
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
                            // toastr.success('Branch loaded successfully!');
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