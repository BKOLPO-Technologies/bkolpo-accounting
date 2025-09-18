@extends('layouts.admin', ['pageTitle' => 'Receive Payment'])
@section('admin')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}"
                                    style="text-decoration: none; color: black;">Home</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('project.advance.receipt.payment.index') }}"
                                    class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('project.advance.receipt.payment.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    {{-- Project --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="project_id">Project</label>
                                        <div class="input-group">
                                            <select name="project_id" id="project_id"
                                                class="form-control select2 @error('project_id') is-invalid @enderror"
                                                required>
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        data-reference="{{ $project->reference_no }}"
                                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                        {{ $project->project_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <div class="input-group-append">
                                                <!-- Button to trigger modal for adding a new project_id -->
                                                <button class="btn btn-danger" type="button" id="addProjectBtn"
                                                    data-toggle="modal" data-target="#createProjectModal">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('project_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Sales Reference No -->
                                    <div class="col-md-6 mb-3">
                                        <label for="reference_no" class="form-label">Reference No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                            <input type="text" name="reference_no" id="reference_no"
                                                class="form-control @error('reference_no') is-invalid @enderror"
                                                value="{{ old('reference_no', $sale->reference_no ?? '') }}"
                                                placeholder="Enter Reference No" readonly>
                                            @error('reference_no')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <!-- Receive Amount -->
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Receive Amount:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                            <input type="number" class="form-control" id="receive_amount"
                                                name="receive_amount" value="{{ old('receive_amount') }}" step="0.01"
                                                placeholder="Enter Receive Amount" required>
                                        </div>
                                    </div>

                                    <!-- Receive Method -->
                                    <div class="col-md-6 mb-3">
                                        <label for="ledger_group_id" class="form-label">Select Receive Method:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            <select class="form-control" name="payment_method" id="payment_method" required>
                                                <option value="">Choose Receive Method</option>
                                                @foreach ($ledgers as $ledger)
                                                    <option value="{{ $ledger->id }}" data-type="{{ $ledger->type }}"
                                                        {{ old('ledger_id') == $ledger->id ? 'selected' : '' }}>
                                                        {{ $ledger->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Receive Mood -->
                                    <div class="col-md-6 mb-3">
                                        <label for="ledger_group_id" class="form-label">Select Receive Mood:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            <select class="form-control" name="payment_mood" id="payment_mood">
                                                <option value="">Choose Receive Mood</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cheque">Cheque Payment</option>
                                                <option value="bkash">Bkash</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Bank Account Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="bank_account_div" style="display:none;">
                                        <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                            <input type="text" class="form-control" name="bank_account_no"
                                                placeholder="Enter Bank Account No" id="bank_account_no">
                                        </div>
                                    </div>

                                    <!-- Batch Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="bank_batch_div" style="display:none;">
                                        <label for="bank_batch_no" class="form-label">Batch No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                            <input type="text" class="form-control" name="bank_batch_no"
                                                placeholder="Enter Batch No" id="bank_batch_no">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="bank_date_div" style="display:none;">
                                        <label for="bank_date" class="form-label">Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="bank_date" id="bksh_date">
                                        </div>
                                    </div>


                                    <!-- Cheque Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="cheque_no_div" style="display:none;">
                                        <label for="cheque_no" class="form-label">Cheque No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                                            <input type="text" class="form-control" name="cheque_no"
                                                placeholder="Enter Cheque No" id="cheque_no">
                                        </div>
                                    </div>

                                    <!-- Cheque Date (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="cheque_date_div" style="display:none;">
                                        <label for="cheque_date" class="form-label">Cheque Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="cheque_date"
                                                id="to_date">
                                        </div>
                                    </div>

                                    <!-- Bkash Number -->
                                    <div class="col-md-6 mb-3" id="bkash_number_div" style="display:none;">
                                        <label for="bkash_number" class="form-label">Bkash Number:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                            <input type="text" class="form-control" name="bkash_number"
                                                placeholder="Enter Bkash Number" id="bkash_number">
                                        </div>
                                    </div>

                                    <!-- Reference Number -->
                                    <div class="col-md-6 mb-3" id="reference_no_div" style="display:none;">
                                        <label for="reference_no" class="form-label">Reference Number:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <input type="text" class="form-control" name="reference_no"
                                                placeholder="Enter Reference Number" id="reference_no">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="bkash_date_div" style="display:none;">
                                        <label for="bkash_date_div" class="form-label">Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="bkash_date" id="from_date">
                                        </div>
                                    </div>

                                    <!-- Receive Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_date" class="form-label">Receive Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" id="date" class="form-control"
                                                name="payment_date" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <label for="note">Note</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                            <textarea id="description" name="note" class="form-control" rows="3" placeholder="Enter some note">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit Button (Right-Aligned) -->
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Submit Payment
                                    </button>
                                </div>
                            </form>
                            @include('backend.admin.inventory.project.advance.payment.receipt.projectModal')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for creating a new client -->
    @include('backend.admin.inventory.client.client_modal')

    <!-- Modal for creating a new product -->
    @include('backend.admin.inventory.project.product_modal')
    {{-- <div id="modalContainer"></div> --}}
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        // init select2
        $('#project_id').select2({
            width: '100%'
        });

        // set reference on load
        function setRef() {
            var ref = $('#project_id').find(':selected').data('reference') || '';
            $('#reference_no').val(ref);
        }

        setRef(); // load time

        // bind change event using select2 event
        $('#project_id').on('change', function() {
            setRef();
        });
    </script>

    <!-- JS to toggle visibility -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paymentMethodSelect = document.getElementById('payment_method');
            const paymentMoodSelect = document.getElementById('payment_mood');

            const bankAccountDiv = document.getElementById('bank_account_div');
            const bankBatchDiv = document.getElementById('bank_batch_div');
            const chequeNoDiv = document.getElementById('cheque_no_div');
            const chequeDateDiv = document.getElementById('cheque_date_div');
            const bankDateDiv = document.getElementById('bank_date_div');
            const bkashNumberDiv = document.getElementById('bkash_number_div');
            const referenceNoDiv = document.getElementById('reference_no_div');
            const bkashDateDiv = document.getElementById('bkash_date_div');

            // Hide payment mood initially
            paymentMoodSelect.closest('.mb-3').style.display = 'none';

            // Handle Payment Method Change
            paymentMethodSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const paymentType = selectedOption.getAttribute('data-type');

                if (paymentType === 'Bank') {
                    paymentMoodSelect.closest('.mb-3').style.display = 'block';
                } else {
                    paymentMoodSelect.closest('.mb-3').style.display = 'none';
                    hideAllPaymentMoodFields();
                }
            });

            // Handle Payment Mood Change
            paymentMoodSelect.addEventListener('change', function() {
                hideAllPaymentMoodFields();

                const mood = this.value;
                switch (mood) {
                    case 'bank_transfer':
                        bankAccountDiv.style.display = 'block';
                        bankBatchDiv.style.display = 'block';
                        bankDateDiv.style.display = 'block';
                        break;
                    case 'cheque':
                        bankAccountDiv.style.display = 'block';
                        chequeNoDiv.style.display = 'block';
                        chequeDateDiv.style.display = 'block';
                        break;
                    case 'bkash':
                        bkashNumberDiv.style.display = 'block';
                        referenceNoDiv.style.display = 'block';
                        bkashDateDiv.style.display = 'block';
                        break;
                }
            });

            // Function to Hide All Optional Fields
            function hideAllPaymentMoodFields() {
                bankAccountDiv.style.display = 'none';
                bankBatchDiv.style.display = 'none';
                chequeNoDiv.style.display = 'none';
                chequeDateDiv.style.display = 'none';
                bankDateDiv.style.display = 'none';
                bkashDateDiv.style.display = 'none';
                bkashNumberDiv.style.display = 'none';
                referenceNoDiv.style.display = 'none';
            }
        });
    </script>

    <script>
        let activeProductSelect = null;

        function generateProductCode() {
            const randomStr = Math.random().toString(36).substring(2, 7).toUpperCase();
            return 'PRD' + randomStr;
        }

        $(document).ready(function() {
            $('.select2').select2();

            function calculateTotal() {
                let subtotal = 0;
                let totalDiscount = parseFloat($('#total_discount').val()) || 0;
                let transportCost = parseFloat($('#transport_cost').val()) || 0;
                let carryingCharge = parseFloat($('#carrying_charge').val()) || 0;

                // Loop through product rows to calculate subtotal
                $('#product-tbody tr').each(function() {
                    let price = parseFloat($(this).find('.unit-price').val()) || 0;
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let discount = parseFloat($(this).find('.discount').val()) || 0;

                    let rowSubtotal = price * (quantity || 1);
                    let rowTotal = rowSubtotal - discount;

                    subtotal += rowTotal;
                    $(this).find('.subtotal').val(rowSubtotal.toFixed(2));
                    $(this).find('.total').val(rowTotal.toFixed(2));
                });

                $('#subtotal').val(subtotal.toFixed(2));

                // Calculate Net Amount (subtotal - discount + transport cost + carrying charge)
                let netAmount = subtotal - totalDiscount + transportCost + carryingCharge;
                $('#total_netamount').val(netAmount.toFixed(2));

                // Calculate Tax on the net amount
                let taxPercent = $('#include_tax').is(':checked') ? (parseFloat($('#tax').val()) || 0) : 0;
                let taxAmount = (netAmount * taxPercent) / 100;
                $('#tax_amount').val(taxAmount.toFixed(2)); // Display TAX amount

                // Calculate the sum of net amount and tax amount
                let netAmountWithTax = netAmount + taxAmount;

                // Calculate VAT on the sum of net amount + tax amount
                let vatPercent = $('#include_vat').is(':checked') ? (parseFloat($('#vat').val()) || 0) : 0;
                let vatAmount = (netAmountWithTax * vatPercent) / 100;
                $('#vat_amount').val(vatAmount.toFixed(2)); // Display VAT amount

                // Calculate final Grand Total
                let grandTotal = netAmountWithTax + vatAmount;
                $('#grand_total').val(grandTotal.toFixed(2)); // Display Grand Total
            }

            // Trigger calculation on input changes
            $(document).on('input keyup change',
                '.unit-price, .quantity, .discount, #transport_cost, #carrying_charge, #vat, #tax, #total_discount',
                function() {
                    calculateTotal();
                });

            // Listen for changes on dynamically added or existing 'item-select' elements
            $(document).on('change', '.item-select', function() {
                // Get the selected option from the dropdown
                const selectedOption = $(this).find('option:selected');

                // Get unit ID
                const unitId = selectedOption.data('unit');
                const description = selectedOption.data('description');
                const price = selectedOption.data('price');

                $(this).closest('tr').find('.unit-select').val(unitId);
                $(this).closest('tr').find('.item-description').val(description);
                $(this).closest('tr').find('.unit-price').val(price);
                calculateTotal();
            });

            // Add row functionality
            $(document).on('click', '.add-row', function() {
                let newRow = `
                    <tr>

                        <td style="width:20%;">
                            <div class="input-group">
                                <select class="item-select select2 form-control" name="items[]" required>
                                    <option value="">Select Item</option>
                                    @foreach ($products as $product)
                                        <option 
                                            value="{{ $product->id }}" 
                                            data-description="{{ $product->description }}"
                                            data-unit="{{ $product->unit_id }}"
                                            data-price="{{ $product->price }}"
                                        >
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger open-product-modal" data-toggle="modal" data-target="#createProductModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td style="width:15%;">
                            <input type="text" class="item-description form-control" name="items_description[]" required>
                        </td>
                        <td style="width: 11%;">
                            <select name="order_unit[]" class="unit-select form-control" required>
                                <option value="" disabled selected>Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="width:15%;">
                            <input type="number" name="quantity[]" class="form-control quantity" min="1" placeholder="Enter Quantity" required>
                        </td>
                        <td style="width:15%;">
                            <input type="number" name="unit_price[]" class="form-control unit-price" min="0" step="0.01" placeholder="Enter Unit Price" style="text-align: right;" required>
                        </td>
                        <td style="width:15%;">
                            <input type="text" name="total[]" class="form-control total" readonly style="text-align: right;">
                        </td>
                        <td class="col-1">
                            <button type="button" class="btn btn-success btn-sm me-1 add-row">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fas fa-minus"></i>
                            </button>
                        </td>
                    </tr>`;

                // Append the new row to the table body
                $('#product-tbody').append(newRow);

                // Re-initialize select2 on new selects
                //$('#product-tbody .item-select').last().select2();

                // Wait for DOM paint then init select2
                setTimeout(() => {
                    $('#product-tbody .item-select').last().select2();
                }, 1);
            });


            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // VAT/TAX checkbox toggles
            $('#include_vat').on('change', function() {
                $('#vat').prop('disabled', !this.checked);
                calculateTotal();
            });

            $('#include_tax').on('change', function() {
                $('#tax').prop('disabled', !this.checked);
                calculateTotal();
            });

            // Manual change in VAT/TAX input triggers calculation
            $('#vat, #tax').on('input keyup change', function() {
                calculateTotal();
            });

            // Initialize states
            $('#vat').prop('disabled', !$('#include_vat').is(':checked'));
            $('#tax').prop('disabled', !$('#include_tax').is(':checked'));

            // Initial calculation
            calculateTotal();
        });

        $('#createClientForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            let formData = $(this).serialize(); // Get form data

            $.ajax({
                url: '{{ route('admin.client2.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Check if the supplier was created successfully
                    if (response.success) {
                        // Close the modal
                        $('#createClientModal').modal('hide');

                        // Clear form inputs
                        $('#createClientForm')[0].reset();

                        // Create a new option with data attributes
                        let newOption = $('<option>', {
                            value: response.client.id,
                            text: response.client.name,
                            'data-name': response.client.name,
                            'data-company': response.client.company,
                            'data-phone': response.client.phone,
                            'data-email': response.client.email
                        });

                        // Insert new supplier AFTER "Select Vendor" option
                        $('#client_id option:first').after(newOption);

                        // Select the newly added supplier
                        $('#client_id').val(response.client.id).trigger('change');

                        // Show success message
                        toastr.success('Client added successfully!');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(response) {
                    // Handle error (validation errors, etc.)
                    let errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`#new_client_${field}`).addClass('is-invalid');
                        $(`#new_client_${field}`).after(
                            `<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            });
        });

        $('#createProductForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route('admin.Product2.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        // Close the modal
                        $('#createProductModal').modal('hide');

                        // Clear form inputs
                        $('#createProductForm')[0].reset();

                        // Create a new option with data attributes
                        let newOption = $('<option>', {
                            value: response.product.id,
                            text: response.product.name,
                            'data-name': response.product.name,
                            'data-description': response.product.description,
                            'data-unit': response.product.unit_id,
                            'data-price': response.product.price,
                        });

                        // ✅ Use the dynamically tracked dropdown
                        if (activeProductSelect) {
                            activeProductSelect.find('option:first').after(newOption);
                            activeProductSelect.val(response.product.id).trigger('change');

                            // Re-initialize Select2 if needed
                            activeProductSelect.select2();
                        }

                        // Show success message
                        toastr.success('Product added successfully!');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(response) {}
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const projectSelect = document.getElementById("project_id");
            const refInput = document.getElementById("reference_no");

            projectSelect.addEventListener("change", function() {
                let selectedOption = this.options[this.selectedIndex];
                let refNo = selectedOption.getAttribute("data-reference") || "";

                // Auto set ref no
                refInput.value = refNo;
            });
        });
    </script>
@endpush
