@extends('layouts.admin', ['pageTitle' => 'Contra Voucher Create'])
@section('admin')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
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
                                    <h4 class="mb-0">Create Contra Voucher</h4>
                                    
                                    <!-- Back to List Button -->
                                    <a href="#" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="#" enctype="multipart/form-data">
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
                                            <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror" required>
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
                                            <select name="branch_id" id="branch_id" class="form-control @error('branch_id') is-invalid @enderror" required>
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
                                            <input type="date" id="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required />
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
                                                <thead class="table-light">
                                                    <tr style="background:#dcdcdc; text-align:center;">
                                                        <th>Dr/Cr</th>
                                                        <th>Account</th>
                                                        <th>Reference No</th>
                                                        <th>Description</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Debit Row (Cash/Bank) -->
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="transaction_type[]" value="Debit" readonly></td>
                                                        <td>
                                                            <select class="form-control cash-bank-select" name="ledger_id[]" required>
                                                                <option value="">Select Cash/Bank Account</option>
                                                                @foreach($cashBankAccounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No" required></td>
                                                        <td><textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea></td>
                                                        <td><input type="number" class="form-control text-end debit-amount" name="debit[]" placeholder="Enter Amount" required step="0.01" min="0"></td>
                                                        <td><input type="hidden" name="credit[]" value="0"></td>
                                                    </tr>
                                                    
                                                    <!-- Credit Row (Cash/Bank) -->
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="transaction_type[]" value="Credit" readonly></td>
                                                        <td>
                                                            <select class="form-control cash-bank-select" name="ledger_id[]" required>
                                                                <option value="">Select Cash/Bank Account</option>
                                                                @foreach($cashBankAccounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No" required></td>
                                                        <td><textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea></td>
                                                        <td><input type="hidden" name="debit[]" value="0"></td>
                                                        <td><input type="number" class="form-control text-end credit-amount" name="credit[]" placeholder="Enter Amount" required step="0.01" min="0"></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                                        <td class="text-end"><strong id="debitTotal">৳0.00</strong></td>
                                                        <td class="text-end"><strong id="creditTotal">৳0.00</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Post Contra Voucher
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
        // Company to branch dropdown
        $('#company_id').on('change', function () {
            let companyId = $(this).val();
            let branchSelect = $('#branch_id');

            // Clear previous options
            branchSelect.empty();
            branchSelect.append('<option value="">Select Branch</option>');

            if (companyId) {
                $.ajax({
                    url: '/admin/contra-voucher/get-branches/' + companyId,
                    type: 'GET',
                    success: function (response) {
                        branchSelect.empty();
                        branchSelect.append('<option value="">Select Branch</option>');

                        if (response.success) {
                            const branch = response.branch;
                            branchSelect.append(
                                `<option value="${branch.id}">${branch.name}</option>`
                            );
                        } else {
                            toastr.warning(response.message || 'No branch found for this company.');
                        }
                    },
                    error: function () {
                        toastr.error('Error retrieving branches. Please try again.');
                    },
                });
            }
        });

        // Amount synchronization
        $('.debit-amount, .credit-amount').on('input', function() {
            if ($(this).hasClass('debit-amount')) {
                $('.credit-amount').val($(this).val());
            } else {
                $('.debit-amount').val($(this).val());
            }
            updateTotals();
        });

        // Account selection validation
        $('.cash-bank-select').on('change', function() {
            let debitAccount = $('select[name="ledger_id[]"]').first().val();
            let creditAccount = $('select[name="ledger_id[]"]').last().val();
            
            if (debitAccount && creditAccount && debitAccount === creditAccount) {
                toastr.error('Debit and Credit accounts cannot be the same');
                $(this).val('');
                updateTotals();
            }
        });

        function updateTotals() {
            let debit = parseFloat($('.debit-amount').val()) || 0;
            let credit = parseFloat($('.credit-amount').val()) || 0;
            
            $('#debitTotal').text('৳' + debit.toFixed(2));
            $('#creditTotal').text('৳' + credit.toFixed(2));
            
            // Check if accounts are selected and valid
            let debitAccount = $('select[name="ledger_id[]"]').first().val();
            let creditAccount = $('select[name="ledger_id[]"]').last().val();
            
            if (debit !== credit || !debitAccount || !creditAccount || debitAccount === creditAccount) {
                $('#debitTotal, #creditTotal').css('color', 'red');
                $('button[type="submit"]').prop('disabled', true);
            } else {
                $('#debitTotal, #creditTotal').css('color', 'black');
                $('button[type="submit"]').prop('disabled', false);
            }
        }

        // Initialize date picker
        $('#date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
@endpush