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

                                <a data-toggle="modal" data-target="#importModal" class="btn btn-success">
                                    <i class="fa-solid fa-download"></i> Click to Import
                                </a>

                                <a href="{{ route('journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('journal-voucher.capital.store') }}" enctype="multipart/form-data">
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
                                        <table class="table table-bordered border-secondary" id="capital-table">
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
                                                            <input type="text" class="form-control" name="group[]" 
                                                                placeholder="Enter Group No" value="{{ old("group.$i") }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="sub_group[]" 
                                                                placeholder="Enter Sub-Group No" value="{{ old("sub_group.$i") }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="ledger[]" 
                                                                placeholder="Enter Ledger No" value="{{ old("ledger.$i") }}">
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

<!-- Excell input Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="excelFile">Select Excel File</label>
                        <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xls,.xlsx" required>
                    </div>
                    <button type="button" class="btn btn-primary" id="submitImport">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('js')

<script>

    document.getElementById("submitImport").addEventListener("click", function() {
        let fileInput = document.getElementById("excelFile");
        if (fileInput.files.length === 0) {
            alert("Please select an Excel file before submitting.");
            return;
        }

        let file = fileInput.files[0];
        let reader = new FileReader();

        reader.onload = function(e) {
            let data = new Uint8Array(e.target.result);
            let workbook = XLSX.read(data, { type: "array" });

            let sheetName = workbook.SheetNames[0]; // Get the first sheet
            let sheetData = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

            console.log("Excel Data:", sheetData); // Debugging: Log the sheet data

            populateTable(sheetData); // Call function to populate table
            $("#importModal").modal("hide"); // Hide modal after processing
        };

        reader.readAsArrayBuffer(file);
    });

    function populateTable(data) {
        let tableBody = document.querySelector("#capital-table tbody");
        console.log("Populating Table with Data:", data);

        let rows = tableBody.querySelectorAll("tr"); // Get all existing rows

        for (let i = 0; i < rows.length; i++) {
            let row = rows[i];
            let rowData = data[i + 1] || []; // Skip header row in data

            let type = rowData[0] || '';
            let group = rowData[1] || '';
            let subGroup = rowData[2] || '';
            let ledger = rowData[3] || '';
            let openingBalance = parseFloat(rowData[4]) || 0; // Convert to number

            // Assign values to inputs
            row.querySelector('[name="type[]"]').value = type;
            row.querySelector('[name="group[]"]').value = group;
            row.querySelector('[name="sub_group[]"]').value = subGroup;
            row.querySelector('[name="ledger[]"]').value = ledger;

            // Conditionally assign openingBalance to debit or credit
            if (type.toLowerCase() === "asset") {
                row.querySelector('[name="debit[]"]').value = openingBalance;
                row.querySelector('[name="credit[]"]').value = ''; // Clear credit
            } else if (type.toLowerCase() === "liability") {
                row.querySelector('[name="credit[]"]').value = openingBalance;
                row.querySelector('[name="debit[]"]').value = ''; // Clear debit
            } else {
                row.querySelector('[name="debit[]"]').value = '';
                row.querySelector('[name="credit[]"]').value = '';
            }

            // row.querySelector('[name="type[]"]').value = rowData[0] || '';
            // row.querySelector('[name="group[]"]').value = rowData[1] || '';
            // row.querySelector('[name="sub_group[]"]').value = rowData[2] || '';
            // row.querySelector('[name="ledger[]"]').value = rowData[3] || '';
            // row.querySelector('[name="debit[]"]').value = rowData[4] || '';
            // row.querySelector('[name="credit[]"]').value = rowData[5] || '';
        }

        calculateTotals();
    }

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
