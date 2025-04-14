{{-- Project Wise Table Calculation --}}
@extends('layouts.admin', ['pageTitle' => 'Sales'])
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
                            <a href="{{ route('admin.sale.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.sale.update', $sale->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="product_ids" id="product_ids" value="{{ $product_ids }}">
                            <input type="hidden" name="quantities" id="quantities" value="{{ $quantities }}">
                            <input type="hidden" name="prices" id="prices" value="{{ $prices }}">
                            <input type="hidden" name="discounts" id="discounts" value="{{ $discounts }}">

                            <div class="row">
                                <!-- Supplier Select -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="client">Client</label>
                                    <div class="input-group">
                                        <!-- --- -->
                                        <select name="client" id="client" class="form-control select2 @error('client') is-invalid @enderror">
                                            <option value="" disabled>Select Customer</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                    data-name="{{ $client->name }}" 
                                                    data-company="{{ $client->company }}" 
                                                    data-phone="{{ $client->phone }}" 
                                                    data-email="{{ $client->email }}"
                                                    {{ (old('client', $sale->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- --- -->
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" id="addClientBtn" data-toggle="modal" data-target="#createClientModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('client')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Project Select with Search Feature -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_id">Project</label>
                                    <div class="input-group">
                                        <select name="project_id" id="project_id" class="form-control select2 @error('project_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}"
                                                    data-items='@json($project->items)'
                                                    @if(old('project_id', $sale->project_id) == $project->id) selected @endif>
                                                    {{ $project->project_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('project_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Invoice No -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="invoice_no">Invoice No</label>

                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $sale->invoice_no) }}" readonly />

                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Supplier Details Table -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    
                                    <table class="table table-bordered" id="sale-details-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Group Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sale-details-body">
                                            <td>{{ $sale->client->name }}</td>
                                            <td>{{ $sale->client->company }}</td>
                                            <td>{{ $sale->client->phone }}</td>
                                            <td>{{ $sale->client->email }}</td>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                                
                            <!-- Product Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="product-table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Order Unit</th>
                                                    <th>Speciphication</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-tbody">
                                                @foreach ($sale->saleProducts as $product)
                                                <tr>
                                                    <td class="col-3">
                                                        <input type="text" name="description[]" class="form-control" value="{{ $product->item->items }}" placeholder="Enter Item Description" readonly required>
                                                        <input type="hidden" name="item_id[]" value="{{ $product->item->id }}">
                                                    </td>
                                                    
                                                    <td>
                                                        <select name="order_unit[]" class="form-control" required>
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit->id }}" {{ $unit->id == $product->item->unit_id ? 'selected' : '' }}>
                                                                    {{ $unit->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>                                                    

                                                    <td>
                                                        <input type="text" name="specifications[]" class="form-control specifications" value="{{ $product->item->items_description }}" required readonly>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="quantity[]" class="form-control quantity" value="{{ $product->quantity }}" min="1" step="1" required>
                                                    </td>

                                                    <td style="width:7% !important;">
                                                        <input type="number" name="unit_price[]" class="form-control unit-price" value="{{ $product->price }}" min="0" step="0.01" required style="text-align: right;">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total[]" class="form-control total" readonly value="{{ ((($product->price) * ($product->quantity)) - ($product->discount)) }}" style="text-align: right;">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- --- --}}
                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <div class="row w-100">
                                    <div class="col-12 col-lg-6 mb-2">
                                    </div>
                                    <div class="col-12 col-lg-6 mb-2">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><label for="subtotal">Total Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ $sale->subtotal }}" readonly style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_discount">Discount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_discount" name="total_discount" class="form-control" step="0.01" placeholder="Enter Discount" value="{{ $sale->discount }}" oninput="updateTotal()" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_netamount">Net Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_netamount" name="total_netamount" class="form-control" readonly placeholder="0.00" value="{{ $sale->total_netamount }}" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_tax" id="include_tax" {{ $sale->tax > 0 ? 'checked' : '' }}>
                                                            <label for="include_tax" class="me-3">
                                                                Include TAX (%)
                                                                <input type="number" name="tax" id="tax" value="{{ $sale->tax }}" min="0"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    step="0.01" placeholder="Enter TAX"
                                                                    style="width: 100px; margin-left: 10px;"  />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 mb-3 tax-fields">
                                                            <input type="text" id="tax_amount" name="tax_amount" value="{{ $sale->tax_amount }}" class="form-control" readonly placeholder="TAX Amount" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            <input type="checkbox" name="include_vat" id="include_vat" {{ $sale->vat > 0 ? 'checked' : '' }}>
                                                            <label for="include_vat">
                                                                Include VAT (%)
                                                                <input type="number" id="vat" name="vat" value="{{ $sale->vat }}" min="0"
                                                                       class="form-control form-control-sm vat-input"
                                                                       step="0.01" placeholder="Enter VAT"
                                                                       style="width: 70px; display: inline-block; margin-left: 10px;" />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 vat-fields">
                                                            <input type="text" id="vat_amount" name="vat_amount" value="{{ $sale->vat_amount }}" class="form-control" readonly placeholder="VAT Amount" style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td><label for="grand_total">Grand Total</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="{{ $sale->grand_total }}" readonly style="text-align: right;"/>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- --- --}}

                            <hr>

                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the description">{{ $sale->description }}</textarea>
                            </div>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>Update Invoice</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>

<!-- Modal for creating a new Client -->
@include('backend.admin.inventory.client.client_modal')

@endsection

@push('js')

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Show table if there's a selected client on page load
        const selectedOption = $('#client').find(':selected');
        if (selectedOption.length && selectedOption.val()) {
            updateClientDetailsTable(selectedOption);
        }

        // Client dropdown change event
        $('#client').on('change', function () {
            const selected = $(this).find(':selected');
            updateClientDetailsTable(selected);
        });

        function updateClientDetailsTable(option) {
            const name = option.data('name') || 'N/A';
            const company = option.data('company') || 'N/A';
            const phone = option.data('phone') || 'N/A';
            const email = option.data('email') || 'N/A';

            // Update table
            $('#sale-details-table').show();
            $('#sale-details-body').html(`
                <tr>
                    <td>${name}</td>
                    <td>${company}</td>
                    <td>${phone}</td>
                    <td>${email}</td>
                </tr>
            `);
        }

        // Create Client Modal Submit
        $('#createClientForm').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route('admin.client2.store') }}',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#createClientModal').modal('hide');
                        $('#createClientForm')[0].reset();

                        // Create new <option> with data attributes
                        let newOption = $('<option>', {
                            value: response.client.id,
                            text: response.client.name,
                            'data-name': response.client.name,
                            'data-company': response.client.company,
                            'data-phone': response.client.phone,
                            'data-email': response.client.email
                        });

                        // Insert after first (placeholder) option
                        $('#client option:first').after(newOption);

                        // Select the new client
                        $('#client').val(response.client.id).trigger('change');

                        toastr.success('Client added successfully!');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function (response) {
                    let errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`#new_client_${field}`).addClass('is-invalid');
                        $(`#new_client_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            });
        });
    });
</script>

<script>
    const units = @json($units);
    console.log(units);
</script>

<script>
    $(document).ready(function () {
    // On project change
    $('#project_id').on('change', function () {
        const selectedOption = $(this).find(':selected');
        const items = selectedOption.data('items');

        //console.log(items);

        if (!items || items.length === 0) {
            toastr.warning('No items found for this project.');
            return;
        }

        $('#product-table tbody').empty();

        items.forEach(item => {
            const itemId = item.id;
            const itemDesc = item.items || 'N/A';
            const itemQuantity = parseFloat(item.quantity || 0);
            const itemPrice = parseFloat(item.unit_price || 0);
            const itemTotal = itemQuantity * itemPrice;

            let unitOptions = '<option value="" disabled>Select Unit</option>';
            units.forEach(unit => {
                const selected = unit.id === item.unit_id ? 'selected' : '';
                unitOptions += `<option value="${unit.id}" ${selected}>${unit.name}</option>`;
            });

            const row = `
                <tr data-product-id="${itemId}">
                    <td class="col-3">
                        <input type="text" name="description[]" class="form-control" value="${itemDesc}" placeholder="Enter Item Description" readonly required>
                        <input type="hidden" name="item_id[]" value="${itemId}">
                    </td>
                    <td class="col-2">
                        <select name="order_unit[]" class="form-control" required>
                            ${unitOptions}
                        </select>
                    </td>
                    <td class="col-2">
                        <input type="number" name="quantity[]" class="form-control quantity" value="${itemQuantity}" min="1" step="1" required>
                    </td>
                    <td class="col-2">
                        <input type="number" name="unit_price[]" class="form-control unit-price" value="${itemPrice}" min="0" step="0.01" required style="text-align: right;">
                    </td>
                    <td class="col-2">
                        <input type="text" name="total[]" class="form-control total" readonly value="${itemTotal.toFixed(2)}" style="text-align: right;">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;

            $('#product-table tbody').append(row);
        });

        updateTotal();
    });

    // Calculate totals
    function updateTotal() {
        let subtotal = 0;

        $('#product-table tbody tr').each(function () {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.unit-price').val()) || 0;
            const total = quantity * price;

            $(this).find('.total').val(total.toFixed(2));
            subtotal += total;
        });

        $('#subtotal').val(subtotal.toFixed(2));

        const discount = parseFloat($('#total_discount').val()) || 0;
        const netAmount = subtotal - discount;
        $('#total_netamount').val(netAmount.toFixed(2));

        // TAX
        const includeTax = $('#include_tax').is(':checked');
        const taxRate = parseFloat($('#tax').val()) || 0;
        const taxAmount = includeTax ? (netAmount * taxRate / 100) : 0;
        $('#tax_amount').val(includeTax ? taxAmount.toFixed(2) : '');

        // Calculate the sum of net amount and tax amount
        let netAmountWithTax = netAmount + taxAmount;

        // VAT
        const includeVAT = $('#include_vat').is(':checked');
        const vatRate = parseFloat($('#vat').val()) || 0;
        const vatAmount = includeVAT ? (netAmountWithTax * vatRate / 100) : 0;
        $('#vat_amount').val(includeVAT ? vatAmount.toFixed(2) : '');

        const grandTotal = netAmountWithTax + vatAmount;
        $('#grand_total').val(grandTotal.toFixed(2));
    }

    // Update when quantity or price changes
    $(document).on('input', '.quantity, .unit-price, #total_discount, #tax, #vat', updateTotal);

    // Handle VAT and TAX checkbox toggle
    $('#include_tax, #include_vat').on('change', function () {
        $('#tax').prop('disabled', !$('#include_tax').is(':checked'));
        $('#vat').prop('disabled', !$('#include_vat').is(':checked'));
        updateTotal();
    });

    // Remove product row
    $('#product-table').on('click', '.remove-product', function () {
        $(this).closest('tr').remove();
        updateTotal();
    });
});

</script>
    
@endpush
