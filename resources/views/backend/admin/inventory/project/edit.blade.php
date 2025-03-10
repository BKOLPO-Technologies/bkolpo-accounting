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

                        <form method="POST" action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                            value="{{ $project->project_name }}" required>
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
                                            value="{{ $project->project_location }}" required>
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
                                            value="{{ $project->project_coordinator }}" required>
                                    </div>
                                    @error('project_coordinator')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Customer Select -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="customer">Customer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <select name="client_id" id="client_id" 
                                            class="form-control select2 @error('client_id') is-invalid @enderror">
                                            <option value="" disabled>Select Customer</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                    data-name="{{ $client->name }}" 
                                                    data-company="{{ $client->company }}" 
                                                    data-phone="{{ $client->phone }}" 
                                                    data-email="{{ $client->email }}"
                                                    {{ (old('client_id', $project->client->id ?? '') == $client->id) ? 'selected' : '' }}>
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
                                            value="{{ old('reference_no', $project->reference_no ?? $referance_no) }}" readonly />
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
                                            value="{{ old('schedule_date', $project->schedule_date ?? now()->format('Y-m-d')) }}"/>
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
                                                    @foreach($project->items as $item)
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="items[]" class="form-control" 
                                                                    value="{{ old('items.' . $loop->index, $item->items) }}" 
                                                                    placeholder="Enter Item Description" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="order_unit[]" class="form-control" 
                                                                    value="{{ old('order_unit.' . $loop->index, $item->order_unit) }}" 
                                                                    placeholder="Enter Unit" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="unit_price[]" class="form-control unit-price" 
                                                                    value="{{ old('unit_price.' . $loop->index, $item->unit_price) }}" 
                                                                    placeholder="Enter Unit Price" min="0" required>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="quantity[]" class="form-control quantity" 
                                                                    value="{{ old('quantity.' . $loop->index, $item->quantity) }}" 
                                                                    placeholder="Enter Quantity" min="1" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="subtotal[]" class="form-control subtotal" 
                                                                    value="{{ old('subtotal.' . $loop->index, $item->subtotal) }}" 
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[]" class="form-control discount" 
                                                                    value="{{ old('discount.' . $loop->index, $item->discount) }}" 
                                                                    placeholder="Enter Discount" min="0">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="total[]" class="form-control total" 
                                                                    value="{{ old('total.' . $loop->index, $item->total) }}" 
                                                                    readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                @if($loop->first)
                                                                    <!-- First row gets the "+" button -->
                                                                    <button type="button" class="btn btn-success btn-sm add-row">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                @else
                                                                    <!-- Other rows get the "-" button -->
                                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
                                                <input type="number" id="total_discount" name="total_discount" class="form-control" placeholder="Enter Total Discount" value="{{ old('total_discount', $project->total_discount) }}">
                                            </div>
                                            
                                        </div>

                                        <!-- Second Row: Remaining fields (Transport Cost, Carrying Charge, Vat, Tax, Grand Total) -->
                                        <div class="row w-100">
                                            <!-- Transport Cost -->
                                            <div class="col-12 col-lg-6 mb-2">
                                                <label for="transport_cost">Transport Cost</label>
                                                <input type="number" min="0" id="transport_cost" name="transport_cost" class="form-control" placeholder="Enter Transport Cost" value="{{ old('transport_cost', $project->transport_cost) }}"/>
                                            </div>
                                    
                                            <!-- Carrying/Labour Charge -->
                                            <div class="col-12 col-lg-6 mb-2">
                                                <label for="carrying_charge">Carrying/Labour Charge</label>
                                                <input type="number" min="0" id="carrying_charge" name="carrying_charge" class="form-control" placeholder="Enter Carrying Charge" value="{{ old('carrying_charge', $project->carrying_charge) }}"/>
                                            </div>
                                    
                                            <!-- Vat -->
                                            <div class="col-12 col-lg-6 mb-2">
                                                <label for="vat">Vat</label>
                                                <input type="number" min="0" id="vat" name="vat" class="form-control" placeholder="Enter Vat" value="{{ old('vat', $project->vat) }}" />
                                            </div>
                                    
                                            <!-- Tax -->
                                            <div class="col-12 col-lg-6 mb-3">
                                                <label for="tax">Tax</label>
                                                <input type="number" min="0" id="tax" name="tax" class="form-control" placeholder="Enter Tax" value="{{ old('tax', $project->tax) }}" />
                                            </div>
                                            
                                            {{-- <div class="col-6 mb-2">
                                            </div> --}}
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
                                        <option value="Ongoing" {{ (old('project_type', $project->project_type) == 'Ongoing') ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Running" {{ (old('project_type', $project->project_type) == 'Running') ? 'selected' : '' }}>Running</option>
                                        <option value="Upcoming" {{ (old('project_type', $project->project_type) == 'Upcoming') ? 'selected' : '' }}>Upcoming</option>
                                        <option value="Completed" {{ (old('project_type', $project->project_type) == 'Completed') ? 'selected' : '' }}>Completed</option>
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
                                <textarea id="note" name="description" class="form-control" rows="3" placeholder="Enter Some Note">{{ old('description', $project->description) }}</textarea>
                            </div>                            
                            <!-- Terms & Conditions -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="note">Terms & Conditions</label>
                                <textarea id="summernote" name="terms_conditions" class="form-control" rows="3" placeholder="Enter Terms & Conditions">{{ old('terms_conditions', $project->terms_conditions) }}</textarea>
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
                <td><input type="text" name="order_unit[]" class="form-control" placeholder="Enter Unit" required></td>
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

@endpush
