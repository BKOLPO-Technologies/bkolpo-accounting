@extends('layouts.admin', ['Project Create' => 'Sales'])
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                            <a href="{{ route('projects.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Project Name -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_name">Project Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
                                        </div>
                                        <input type="text" name="project_name" id="project_name" 
                                            class="form-control @error('project_name') is-invalid @enderror" 
                                            placeholder="Enter Project Name" 
                                            value="{{ old('project_name') }}" required>
                                    </div>
                                    @error('project_name')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <!-- Project Location -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_location">Project Location</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="text" name="project_location" id="project_location" 
                                            class="form-control @error('project_location') is-invalid @enderror" 
                                            placeholder="Enter Project Location" 
                                            value="{{ old('project_location') }}" required>
                                    </div>
                                    @error('project_location')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Project Coordinator -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_coordinator">Project Coordinator</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        </div>
                                        <input type="text" name="project_coordinator" id="project_coordinator" 
                                            class="form-control @error('project_coordinator') is-invalid @enderror" 
                                            placeholder="Enter Project Coordinator" 
                                            value="{{ old('project_coordinator') }}" required>
                                    </div>
                                    @error('project_coordinator')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Client Select -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="customer">Client</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="client_id" id="client_id" 
                                            class="form-control select2 @error('client_id') is-invalid @enderror">
                                            <option value="" disabled {{ old('client') ? '' : 'selected' }}>Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                    data-name="{{ $client->name }}" 
                                                    data-company="{{ $client->company }}" 
                                                    data-phone="{{ $client->phone }}" 
                                                    data-email="{{ $client->email }}"
                                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" id="addClientBtn" data-toggle="modal" data-target="#createClientModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('client_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Reference No -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="referance_no">Reference No</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type="text" id="referance_no" name="reference_no" 
                                            class="form-control @error('referance_no') is-invalid @enderror" 
                                            value="{{ old('referance_no', $referance_no) }}" readonly />
                                    </div>
                                    @error('referance_no')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Project Schedule Date -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="schedule_date">Project Schedule Date</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="date" name="schedule_date" 
                                            class="form-control @error('schedule_date') is-invalid @enderror" 
                                            value="{{ old('schedule_date', now()->format('Y-m-d')) }}" />
                                    </div>
                                    @error('schedule_date')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Product Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="product-table" class="table table-bordered">
                                            <table id="product-table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Item Description</th>
                                                        <th>Order Unit</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price</th>
                                                        {{-- <th>Subtotal</th>
                                                        <th>Discount</th> --}}
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-tbody">
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="items[]" class="form-control" placeholder="Enter Item Description" required>
                                                        </td>
                                                        <td>
                                                            <select name="order_unit[]" class="form-control" required>
                                                                <option value="" disabled selected>Select Unit</option>
                                                                @foreach($units as $unit)
                                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>                                                        
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control quantity" placeholder="Enter Quantity" min="1" step="0.01" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="unit_price[]" class="form-control unit-price" placeholder="Enter Unit Price" min="0" step="0.01" required style="text-align: right;">
                                                        </td>
                                                        {{-- <td>
                                                            <input type="text" name="subtotal[]" class="form-control subtotal"  readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="discount[]" class="form-control discount" placeholder="Enter Discount" min="0" step="0.01">
                                                        </td> --}}
                                                        <td>
                                                            <input type="text" name="total[]" class="form-control total" readonly style="text-align: right;">
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-success btn-sm add-row"><i class="fas fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                                                                     
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <!-- First Row: Subtotal and Total Discount -->
                                <div class="row w-100">
                                    <div class="col-12 col-lg-6 mb-2">
                                    </div>
                                    <div class="col-12 col-lg-6 mb-2">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <!-- Subtotal and Discount Row -->
                                                <tr>
                                                    <td><label for="subtotal">Total Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="subtotal" name="total_subtotal" class="form-control" value="0" readonly style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_discount">Discount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_discount" name="total_discount" class="form-control" step="0.01" placeholder="Enter Discount" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_netamount">Net Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_netamount" name="total_netamount" class="form-control" readonly placeholder="0.00" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                
                                                <!-- Include VAT and TAX Checkboxes -->
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_tax" id="include_tax">
                                                           <!-- Include TAX -->
                                                            <label for="include_tax" class="me-3">
                                                                Include TAX (%)
                                                                <input type="number" name="tax" id="tax" value="{{ $tax }}" min="0"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    step="0.01" placeholder="Enter TAX"
                                                                    style="width: 70px; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 tax-fields">
                                                            <input type="text" id="tax_amount" name="tax_amount" class="form-control" readonly placeholder="TAX Amount" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_vat" id="include_vat">
                                                            <label for="include_vat">
                                                                Include VAT (%)
                                                                <input type="number" id="vat" name="vat" value="{{ $vat }}" min="0"
                                                                       class="form-control form-control-sm vat-input"
                                                                       step="0.01" placeholder="Enter VAT"
                                                                       style="width: 70px; display: inline-block; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 vat-fields">
                                                            <input type="text" id="vat_amount" name="vat_amount" class="form-control" readonly placeholder="VAT Amount" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <!-- Grand Total Row -->
                                                <tr>
                                                    <td><label for="grand_total">Grand Total</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="0" readonly style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <!-- Project Type -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="project_type">Project Type</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cogs"></i></span> <!-- Example icon -->
                                    </div>
                                    <select name="project_type" id="project_type" class="form-control @error('project_type') is-invalid @enderror" required>
                                        <option value="" disabled>Select Project Type</option>
                                        {{-- <option value="ongoing" {{ old('project_type') == 'ongoing' ? 'selected' : '' }}>Ongoing</option> --}}
                                        <option value="Running" {{ old('project_type') == 'Running' ? 'selected' : '' }}>Running</option>
                                        <option value="upcoming" {{ old('project_type') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="completed" {{ old('project_type') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                @error('project_type')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Note -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="note">Note</label>
                                <textarea id="note" name="description" class="form-control" rows="3" placeholder="Enter Some Note"></textarea>
                            </div>
                            <!-- Terms & Conditions -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="note">Terms & Conditions</label>
                                <textarea id="summernote" name="terms_conditions" class="form-control" rows="3" placeholder="Enter Terms & Conditions"></textarea>
                            </div>
                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>

<!-- Modal for creating a new client -->
@include('backend.admin.inventory.client.client_modal')

@endsection
@push('js')
<script>
   $(document).ready(function () {
    $('.select2').select2();

    function calculateTotal() {
        let subtotal = 0;
        let totalDiscount = parseFloat($('#total_discount').val()) || 0;
        let transportCost = parseFloat($('#transport_cost').val()) || 0;
        let carryingCharge = parseFloat($('#carrying_charge').val()) || 0;

        // Loop through product rows to calculate subtotal
        $('#product-tbody tr').each(function () {
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
    $(document).on('input keyup change', '.unit-price, .quantity, .discount, #transport_cost, #carrying_charge, #vat, #tax, #total_discount', function () {
        calculateTotal();
    });

    // Add row
    $(document).on('click', '.add-row', function () {
        let newRow = `
            <tr>
                <td><input type="text" name="items[]" class="form-control" placeholder="Enter Item Description" required></td>
                <td>
                    <select name="order_unit[]" class="form-control" required>
                        <option value="" disabled selected>Select Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="quantity[]" class="form-control quantity" min="1" placeholder="Enter Quantity" required></td>
                <td><input type="number" name="unit_price[]" class="form-control unit-price" min="0" step="0.01" placeholder="Enter Unit Price" required></td>
                <td><input type="text" name="total[]" class="form-control total" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        $('#product-tbody').append(newRow);
    });

    // Remove row
    $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
        calculateTotal();
    });

    // VAT/TAX checkbox toggles
    $('#include_vat').on('change', function () {
        $('#vat').prop('disabled', !this.checked);
        calculateTotal();
    });

    $('#include_tax').on('change', function () {
        $('#tax').prop('disabled', !this.checked);
        calculateTotal();
    });

    // Manual change in VAT/TAX input triggers calculation
    $('#vat, #tax').on('input keyup change', function () {
        calculateTotal();
    });

    // Initialize states
    $('#vat').prop('disabled', !$('#include_vat').is(':checked'));
    $('#tax').prop('disabled', !$('#include_tax').is(':checked'));

    // Initial calculation
    calculateTotal();
});

</script>


<script> 
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

                    // console.log(response.client.id);
                    // console.log(response.client.name);

                    // // Append new supplier to the supplier select dropdown
                    // $('#client_id').append(new Option(response.client.name, response.client.id));

                    // // Re-initialize the select2 to refresh the dropdown
                    // $('#client_id').trigger('change');

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
                    $(`#new_client_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>
@endpush
