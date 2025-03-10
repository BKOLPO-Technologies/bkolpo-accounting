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
                                    {{-- <a href="{{ route('journal-voucher.manually.create')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> 
                                    </a> --}}
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
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control transaction-type" name="transaction_type[]"  value="Debit" readonly>
                                                        </td>

                                                        <td>
                                                            <select class="form-control" name="ledger_id[]">
                                                                <option value="">Select Account</option>
                                                                @foreach($ledgers as $ledger)
                                                                    <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control text-end debit" name="debit[]" placeholder="Enter Debit Amount">
                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success add-debit-row">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control transaction-type" name="transaction_type[]" value="Credit" readonly>
                                                        </td>

                                                        <td>
                                                            <select class="form-control" name="ledger_id[]">
                                                                <option value="">Select Account</option>
                                                                @foreach($ledgers as $ledger)
                                                                    <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control" name="reference_no[]" placeholder="Enter Reference No">
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" name="description[]" rows="1" placeholder="Enter Description"></textarea>
                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control text-end credit" name="credit[]" placeholder="Enter Credit Amount">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success add-credit-row">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" style="text-align: right; padding-right: 1rem;"><strong>Total:</strong></td>
                                                        <td style="text-align: right;"><strong id="debitTotal">৳0.00</strong></td>
                                                        <td style="text-align: right;"><strong id="creditTotal">৳0.00</strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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
    $(document).ready(function () {

        ///////////////////////////
        // Function to add a new debit row after the current debit row
        $(document).on("click", ".add-debit-row", function () {

            let debitRow = $(this).closest("tr");  // Current Debit Row

            // // Check if there's already a row with "Debit" transaction type
            if ($("tr").find(".transaction-type[value='Debit']").length >= 0) {
                
                let newRow = debitRow.clone();  // Clone the row

                // Clear the cloned row fields (except transaction_type)
                newRow.find("input[type='number']").val("");  // Clear debit and credit
                newRow.find("textarea").val("");  // Clear description

                // Remove the "Add Debit Row" button from the cloned row
                newRow.find(".add-debit-row").remove();

                // Update the transaction type for the new row to 'Debit'
                newRow.find(".transaction-type").val("Debit");

                // Insert the new row after the current row
                debitRow.after(newRow);

            }

            // Recalculate the totals after adding a new row
            calculateTotals();
        });

        // Function to add a new credit row after the current credit row
        $(document).on("click", ".add-credit-row", function () {

            let creditRow = $(this).closest("tr");  // Current Credit Row

            // Check if there's already a row with "Credit" transaction type
            if ($("tr").find(".transaction-type[value='Credit']").length >= 0) {

                let newRow = creditRow.clone();  // Clone the row

                // Clear the cloned row fields (except transaction_type)
                newRow.find("input[type='number']").val("");  // Clear debit and credit
                newRow.find("textarea").val("");  // Clear description

                // Remove the "Add Credit Row" button from the cloned row
                newRow.find(".add-credit-row").remove();

                // Update the transaction type for the new row to 'Credit'
                newRow.find(".transaction-type").val("Credit");

                // Insert the new row after the current row
                creditRow.after(newRow);
            }

            // Recalculate the totals after adding a new row
            calculateTotals();
        });
        
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

            // Only update debit total if it has been changed
            if (totalDebit !== parseFloat($("#debitTotal").data("previousTotal"))) {
                $("#debitTotal").text(formatCurrency(totalDebit));
                $("#debitTotal").data("previousTotal", totalDebit);  // Store the previous total for comparison
            }

            // Only update credit total if it has been changed
            if (totalCredit !== parseFloat($("#creditTotal").data("previousTotal"))) {
                $("#creditTotal").text(formatCurrency(totalCredit));
                $("#creditTotal").data("previousTotal", totalCredit);  // Store the previous total for comparison
            }
        }

        function syncDebitCredit(inputField, targetClass, isDebit) {
            let value = inputField.val().trim();
            let currentRow = inputField.closest("tr");
            let targetRow = (targetClass === ".credit") ? currentRow.next() : currentRow.prev();
            let targetField = targetRow.find(targetClass);

            console.log(`${isDebit ? "Debit" : "Credit"} value entered:`, value);

            if (value !== "") {
                targetField.val(value); // Sync values only for Debit input
            } else {
                targetField.val(""); // Clear target field if input is empty
            }

            calculateTotals(); // Update totals
        }

        $(document).on("keyup", ".debit", function () {
            syncDebitCredit($(this), ".credit", true); // Sync Debit -> Credit
        });

        $(document).on("keyup", ".credit", function () {
            calculateTotals(); // Update totals
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
</script>
@endpush
