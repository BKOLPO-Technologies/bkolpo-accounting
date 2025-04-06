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
                                                        <th>Unit Price ({{ bdt() }})</th>
                                                        <th>Quantity</th>
                                                        <th>Subtotal</th>
                                                        <th>Discount</th>
                                                        <th>Total ({{ bdt() }})</th>
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
                                                            <input type="number" name="unit_price[]" class="form-control unit-price" placeholder="Enter Unit Price" min="0" step="0.01" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control quantity" placeholder="Enter Quantity" min="1" step="0.01" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="subtotal[]" class="form-control subtotal"  readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="discount[]" class="form-control discount" placeholder="Enter Discount" min="0" step="0.01">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="total[]" class="form-control total" readonly>
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
                                    <!-- Subtotal -->
                                    <div class="col-12 col-lg-8 mb-2">
                                       
                                    </div>
                            
                                    <!-- Total Discount -->
                                    <div class="col-12 col-lg-4 mb-2">
                                        <div class="row w-100">
                                            <div class="col-12 col-lg-6 mb-2">
                                                <label for="subtotal">Subtotal</label>
                                                <input type="text" id="subtotal" name="total_subtotal" class="form-control" value="0" readonly />
                                            </div>
                                            <div class="col-12 col-lg-6 mb-2">
                                                <label for="total_discount">Total Discount</label>
                                                <input type="number" id="total_discount" name="total_discount" class="form-control" step="0.01" placeholder="Enter Total Discount" />
                                            </div>
                                        </div>

                                        <!-- Second Row: Remaining fields (Transport Cost, Carrying Charge, Vat, Tax, Grand Total) -->
                                        <div class="row w-100">
                                            <!-- Include VAT Checkbox -->
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <div class="form-group clearfix mt-3">
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_vat" id="include_vat">
                                                            <label for="include_vat">
                                                                Include VAT
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Include Tax Checkbox -->
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <div class="form-group clearfix mt-3">
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_tax" id="include_tax">
                                                            <label for="include_tax">
                                                                Include TAX
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <!-- VAT -->
                                            <div class="col-12 col-lg-6 mb-2 vat-fields" style="display:none;">
                                                <label for="vat">VAT</label>
                                                <input type="number" min="0" id="vat" name="vat" class="form-control" step="0.01" readonly placeholder="Enter VAT" />
                                            </div>
                                        
                                            <!-- Tax -->
                                            <div class="col-12 col-lg-6 mb-3 tax-fields" style="display:none;">
                                                <label for="tax">TAX</label>
                                                <input type="number" min="0" id="tax" name="tax" class="form-control" step="0.01" readonly placeholder="Enter Tax" />
                                            </div>
                                        
                                            <!-- Grand Total -->
                                            <div class="col-12 mb-2">
                                                <label for="grand_total">Grand Total</label>
                                                <input type="text" id="grand_total" name="grand_total" class="form-control" value="0" readonly />
                                            </div>
                                        </div>
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
                                        <option value="ongoing" {{ old('project_type') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
        
    });
    
    // All Functionality Calculations
    $(document).ready(function () {
        // Function to calculate totals
        function calculateTotal() {
            let subtotal = 0;
            let totalDiscount = 0;

            // Loop through all product rows
            $('#product-tbody tr').each(function () {
                let price = parseFloat($(this).find('.unit-price').val()) || 0;
                let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                let discount = parseFloat($(this).find('.discount').val()) || 0;

                // Initialize rowSubtotal to 0
                let rowSubtotal = 0;

                // Calculate rowSubtotal based on quantity and price
                if (price && quantity) {
                    rowSubtotal = price * quantity; // When both price and quantity are valid
                } else {
                    rowSubtotal = price; // When quantity is 0 or invalid, use only price
                }

                let rowTotal = rowSubtotal - discount;

                subtotal += rowTotal;
                totalDiscount += discount; // Accumulate row-level discounts

                $(this).find('.subtotal').val(rowSubtotal.toFixed(2));
                $(this).find('.total').val(rowTotal.toFixed(2));
            });

            // Update subtotal and discount values
            $('#subtotal').val(subtotal.toFixed(2));

            // Manually update total discount in the input field and reflect it in the calculation
            // Get the manually entered discount from the total_discount field
            let manualTotalDiscount = parseFloat($('#total_discount').val()) || 0;

            // Update total discount with the sum of product-level discounts and manual discount
            //$('#total_discount').val(manualTotalDiscount.toFixed(2));

            let transportCost = parseFloat($('#transport_cost').val()) || 0;
            let carryingCharge = parseFloat($('#carrying_charge').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;
            let tax = parseFloat($('#tax').val()) || 0;

            // Calculate grand total
            let grandTotal = subtotal - manualTotalDiscount + transportCost + carryingCharge + vat + tax;
            $('#grand_total').val(grandTotal.toFixed(2));
        }

        // Trigger calculation on unit price, quantity, discount, and total_discount fields
        $(document).on('input keyup', '.unit-price, .quantity, .discount, #transport_cost, #carrying_charge, #vat, #tax, #total_discount', function () {
            calculateTotal();
        });

        // Add new row dynamically
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
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" min="0" step="0.01" placeholder="Enter Unit Price" required></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" min="1" placeholder="Enter Quantity" required></td>
                    <td><input type="text" name="subtotal[]" class="form-control subtotal" readonly></td>
                    <td><input type="number" name="discount[]" class="form-control discount" min="0" step="0.01" placeholder="Enter Discount"></td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            $('#product-tbody').append(newRow);
        });

        // Remove row and recalculate total
        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        // Initial calculation when the page loads
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

<script>
    // Listen for change events on the Include VAT and Include Tax checkboxes
    document.getElementById('include_vat').addEventListener('change', updateFields);
    document.getElementById('include_tax').addEventListener('change', updateFields);

    function updateFields() {
        // Get the checkbox states
        var includeVat = document.getElementById('include_vat').checked;
        var includeTax = document.getElementById('include_tax').checked;

        // Show or hide the VAT field based on the checkbox
        document.querySelector('.vat-fields').style.display = includeVat ? 'block' : 'none';

        // Show or hide the Tax field based on the checkbox
        document.querySelector('.tax-fields').style.display = includeTax ? 'block' : 'none';

        // Call the function to recalculate the grand total
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        var subtotal = 0; // Example subtotal (replace with actual value)
        var vat = 0;
        var tax = 0;

        // If VAT is included, get the VAT value and calculate
        if (document.getElementById('include_vat').checked) {
            vat = parseFloat(document.getElementById('vat').value) || 0;
        }

        // If Tax is included, get the Tax value and calculate
        if (document.getElementById('include_tax').checked) {
            tax = parseFloat(document.getElementById('tax').value) || 0;
        }

        // Calculate the grand total
        var grandTotal = subtotal;

        if (vat > 0) {
            grandTotal += (subtotal * vat / 100); // Add VAT
        }

        if (tax > 0) {
            grandTotal += (subtotal * tax / 100); // Add Tax
        }

        // Update the grand total input field
        document.getElementById('grand_total').value = grandTotal.toFixed(2);
    }
</script>

@endpush
