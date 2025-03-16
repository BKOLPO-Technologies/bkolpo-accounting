@extends('layouts.admin', ['pageTitle' => 'Journal Voucher Create'])
@section('admin')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('journal-voucher.update', $journal->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Transaction Code (Auto Generated) -->
                                        <div class="col-lg-4 mb-3">
                                            <label for="transaction_code">Voucher No</label>
                                            <input type="text" id="transaction_code" name="transaction_code" class="form-control @error('transaction_code') is-invalid @enderror" value="{{ old('transaction_code', $journal->transaction_code) }}" readonly />
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
                                                    <option value="{{ $company->id }}" {{ (old('company_id', $journal->company_id) == $company->id) ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
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
                                                <option value="{{ $branch->id }}" {{ (old('branch_id', $journal->branch_id) == $branch->id) ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            </select>
                                            @error('branch_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        
                                    </div>

                                    <!-- Date Input -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="transaction_date">Date</label>
                                        <input type="text" id="date" name="transaction_date" 
                                            class="form-control @error('transaction_date') is-invalid @enderror" 
                                            value="{{ old('transaction_date', $journal->transaction_date ?? now()->format('Y-m-d')) }}" />
                                        @error('transaction_date')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                        @enderror
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
                                                        <th>Add</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="transactionTableBody">
                                                    <!-- Debit Section -->
                                                    <tbody id="debitSection">
                                                        @foreach($journal->details as $detail)
                                                            @if($detail->debit > 0)
                                                                <tr>
                                                                    <td><input type="text" class="form-control transaction-type" name="transaction_type[]" value="Debit" readonly></td>
                                                                    <td>
                                                                        <select class="form-control" name="ledger_id[]" required>
                                                                            <option value="">Select Account</option>
                                                                            @foreach($ledgers as $ledger)
                                                                                <option value="{{ $ledger->id }}" {{ $detail->ledger_id == $ledger->id ? 'selected' : '' }}>
                                                                                    {{ $ledger->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control" name="reference_no[]" value="{{ $detail->reference_no }}" required></td>
                                                                    <td><textarea class="form-control" name="description[]" rows="1">{{ $detail->description }}</textarea></td>
                                                                    <td>
                                                                        <input type="number" class="form-control text-end debit" name="debit[]" value="{{ $detail->debit }}" required>
                                                                    </td>
                                                                    <td>
                                                                        <input type="hidden" class="form-control text-end credit" name="credit[]" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-success add-row add-debit-row"><i class="fa fa-plus"></i></button>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row remove-debit-row"><i class="fa fa-minus"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                    
                                                    <!-- Credit Section -->
                                                    <tbody id="creditSection">
                                                        @foreach($journal->details as $detail)
                                                            @if($detail->credit > 0)
                                                                <tr>
                                                                    <td><input type="text" class="form-control transaction-type" name="transaction_type[]" value="Credit" readonly></td>
                                                                    <td>
                                                                        <select class="form-control" name="ledger_id[]" required>
                                                                            <option value="">Select Account</option>
                                                                            @foreach($ledgers as $ledger)
                                                                                <option value="{{ $ledger->id }}" {{ $detail->ledger_id == $ledger->id ? 'selected' : '' }}>
                                                                                    {{ $ledger->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" class="form-control" name="reference_no[]" value="{{ $detail->reference_no }}" required></td>
                                                                    <td><textarea class="form-control" name="description[]" rows="1">{{ $detail->description }}</textarea></td>
                                                                    <td>
                                                                        <input type="hidden" class="form-control text-end debit" name="debit[]" value="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control text-end credit" name="credit[]" value="{{ $detail->credit }}" required>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-success add-row add-credit-row"><i class="fa fa-plus"></i></button>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row remove-credit-row"><i class="fa fa-minus"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </tbody>
                                    
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" style="text-align: right; padding-right: 1rem;"><strong>Total:</strong></td>
                                                        <td style="text-align: right;"><strong id="debitTotal">৳{{ number_format($journal->details->sum('debit'), 2) }}</strong></td>
                                                        <td style="text-align: right;"><strong id="creditTotal">৳{{ number_format($journal->details->sum('credit'), 2) }}</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>                                    
                                    
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Update Post Journal Voucher
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
                        //console.log(response); // Debug: Check the response in the console
                        
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

    // 
    $(document).ready(function () { 
        function updateRowControls(sectionClass) {
            let rows = $(sectionClass + " tr");
            rows.find(".add-row").hide();
            rows.last().find(".add-row").show();
            rows.find(".remove-row").hide();
            if (rows.length > 1) {
                rows.last().find(".remove-row").show();
            }
        }

        function addNewRow(sectionClass, transactionType) {
            let lastRow = $(sectionClass + " tr").last();
            let newRow = lastRow.clone();
            newRow.find("input[type='text'], input[type='number'], textarea").val("");
            newRow.find(".transaction-type").val(transactionType);
            lastRow.after(newRow);
            updateRowControls(sectionClass);
            calculateTotals();
        }

        function removeRow(sectionClass) {
            let rows = $(sectionClass + " tr");
            if (rows.length > 1) {
                rows.last().remove();
                updateRowControls(sectionClass);
                calculateTotals();
            }
        }

        $(document).on("click", ".add-debit-row", function () {
            addNewRow("#debitSection", "Debit");
        });

        $(document).on("click", ".add-credit-row", function () {
            addNewRow("#creditSection", "Credit");
        });

        $(document).on("click", ".remove-debit-row", function () {
            removeRow("#debitSection");
        });

        $(document).on("click", ".remove-credit-row", function () {
            removeRow("#creditSection");
        });

        function calculateTotals() {
            let totalDebit = 0;
            $(".debit").each(function () {
                totalDebit += parseFloat($(this).val()) || 0;
            });

            let creditRows = $("#creditSection tr");

            if (creditRows.length > 0) {
                let remainingAmount = totalDebit;
                creditRows.each(function (index) {
                    if (index === creditRows.length - 1) {
                        // Ensure the last credit value is never negative
                        $(this).find(".credit").val(Math.max(remainingAmount, 0));
                    } else {
                        remainingAmount -= parseFloat($(this).find(".credit").val()) || 0;
                        remainingAmount = Math.max(remainingAmount, 0); // Prevent negative accumulation
                    }
                });
            }

            let totalCredit = 0;
            $(".credit").each(function () {
                totalCredit += parseFloat($(this).val()) || 0;
            });

            $("#debitTotal").text(formatCurrency(totalDebit));
            $("#creditTotal").text(formatCurrency(totalCredit));

            checkTotals(totalDebit, totalCredit);
        }

        function formatCurrency(amount) {
            return '৳' + new Intl.NumberFormat('en-BD', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        $(document).on("keyup", ".debit", function () {
            calculateTotals();
        });

        $(document).on("keyup", ".credit", function () {

            // If credit is manually updated, we don't want the automatic calculation of debit to happen
            // But still need to check totals
            let totalDebit = 0;
            $(".debit").each(function () {
                totalDebit += parseFloat($(this).val()) || 0;
            });
            
            // adjustFirstCredit(totalDebit);

            let totalCredit = 0;
            $(".credit").each(function () {
                totalCredit += parseFloat($(this).val()) || 0;
            });
            $("#creditTotal").text(formatCurrency(totalCredit));
            
            checkTotals(totalDebit, totalCredit);
        });

        function adjustFirstCredit(totalDebit) {
            let creditRows = $("#creditSection tr");
            let firstCreditField = $("#creditSection tr:first .credit");
            let lastCreditField = $("#creditSection tr:last .credit");

            if (creditRows.length > 1) {
                let remainingAmount = totalDebit;

                // Check if C1 is manually edited
                let isC1Edited = firstCreditField.is(":focus");

                if (isC1Edited) {
                    // If C1 is being edited, adjust the last credit field instead
                    let totalFixedCredit = 0;
                    
                    creditRows.each(function (index) {
                        if (index !== creditRows.length - 1) { // Exclude last credit field
                            totalFixedCredit += parseFloat($(this).find(".credit").val()) || 0;
                        }
                    });

                    let lastCreditValue = totalDebit - totalFixedCredit;
                    // lastCreditField.val(Math.max(lastCreditValue, 0));
                    lastCreditField.val(lastCreditValue > 0 ? lastCreditValue : ""); // Set empty if 0

                } else {
                    // If any other field is edited, adjust C1
                    creditRows.each(function (index) {
                        if (index !== 0) {
                            remainingAmount -= parseFloat($(this).find(".credit").val()) || 0;
                        }
                    });

                    //firstCreditField.val(Math.max(remainingAmount, 0));
                    firstCreditField.val(remainingAmount > 0 ? remainingAmount : ""); // Set empty if 0
                }
            }
        }


        function checkTotals(debitTotal, creditTotal) {

            // Round totals to avoid precision issues
            debitTotal = Math.round(debitTotal * 100) / 100;
            creditTotal = Math.round(creditTotal * 100) / 100;

            //console.log("Debit Total:", debitTotal, "Credit Total:", creditTotal);  // Debugging output
            
            // Check if totals are equal
            if (debitTotal !== creditTotal) {
                // Change total fields color to red
                $("#debitTotal").css("color", "red");
                $("#creditTotal").css("color", "red");
                // Disable the submit button
                $("button[type='submit']").attr("disabled", true);
            } else {
                // Reset total fields color to default
                $("#debitTotal").css("color", "black");
                $("#creditTotal").css("color", "black");
                // Enable the submit button
                $("button[type='submit']").attr("disabled", false);
            }
        }

        updateRowControls("#debitSection");
        updateRowControls("#creditSection");
    });
</script>
@endpush