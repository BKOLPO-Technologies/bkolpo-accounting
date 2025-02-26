@extends('layouts.admin', ['pageTitle' => 'Quotation'])
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
                            <a href="{{ route('quotations.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('quotations.update', $quotation->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="product_ids" id="product_ids">
                            <input type="hidden" name="quantities" id="quantities">
                            <input type="hidden" name="prices" id="prices">
                            <input type="hidden" name="discounts" id="discounts">

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
                                                    {{ (old('client', $quotation->client_id ?? '') == $client->id) ? 'selected' : '' }}>
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

                                <!-- Product Select with Search Feature -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="product">Product</label>
                                    <div class="input-group">
                                        <select name="products" id="product" class="form-control select2 @error('product') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->quantity }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Invoice No -->
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label for="invoice_no">Invoice No</label>

                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $quotation->invoice_no) }}" readonly />

                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="invoice_date">Invoice Date</label>

                                    <input type="date" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', $quotation->invoice_date->format('Y-m-d')) }}" readonly />

                                    @error('invoice_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Supplier Details Table -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <!-- <table class="table table-bordered" id="supplier-details-table" style="display: none;"> -->
                                    <table class="table table-bordered" id="sale-details-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sale-details-body">
                                            <td>{{ $quotation->client->name }}</td>
                                            <td>{{ $quotation->client->company }}</td>
                                            <td>{{ $quotation->client->phone }}</td>
                                            <td>{{ $quotation->client->email }}</td>
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
                                                    <th>Product</th>
                                                    <th>Sell Price</th>
                                                    <th>Quantity</th>
                                                    <th>Current Stock</th>
                                                    <th>Subtotal</th>
                                                    <th>Discount</th>
                                                    <th>Total</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if ($quotation->products->isEmpty())
                                                <tr id="no-products-row">
                                                    <td colspan="6" class="text-center">No product found</td>
                                                </tr>
                                            @else
                                            @foreach ($quotation->products as $product)
                                                <input type="hidden" name="product_multiple_id[]" value="{{ $product->id }}">
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td class="col-3">{{ $product->name }}</td>

                                                    <!-- <td class="col-2">{{ number_format($product->price, 2) }}</td> -->

                                                    <td class="col-2">

                                                        <input type="number" class="price-input form-control" value="{{ number_format($product->pivot->price, 2) }}" step="1" data-product-id="{{ $product->id }}" oninput="updateRow(this)">

                                                    </td>

                                                    <td class="col-1">
                                                        <input type="number" class="quantity form-control" value="{{ $product->pivot->quantity }}" min="1"
                                                            data-price="{{ $product->price }}" data-stock="{{ $product->quantity ?? 0 }}" oninput="updateRow(this)" />
                                                    </td>
                                                    <td class="current-stock col-2">
                                                        <span class="badge bg-info">{{ $product->quantity }}</span>
                                                    </td>
                                                    <td class="subtotal">{{ number_format($product->pivot->quantity * $product->price, 2) }}

                                                    </td>
                                                    <td>
                                                        <input type="number" class="product-discount form-control" value="{{ $product->pivot->discount }}" step="1" oninput="updateRow(this)" />
                                                    </td>
                                                    <td class="total">{{ number_format(($product->pivot->quantity * $product->pivot->price) - $product->pivot->discount, 2) }}
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-product">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                                <!-- Dynamic rows will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <!-- Subtotal -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="subtotal">Subtotal</label>
                                    <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ old('subtotal', $subtotal) }}" readonly />
                                </div>

                                <!-- Discount -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="discount">Discount</label>
                                    <input type="number"  min="0" id="discount" name="discount" class="form-control" value="{{ old('discount', $quotation->discount ?? 0) }}" oninput="updateTotal()" />
                                </div>

                                <!-- Total -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="total">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" value="{{ old('total', $subtotal - ($quotation->discount ?? 0)) }}" readonly />
                                </div>
                            </div>
                            
                            <hr>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>Update Quotation</button>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();

        // Client selection event
        $('#client').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const clientId = selectedOption.val();
            const clientName = selectedOption.data('name') || 'N/A';
            const clientCompany = selectedOption.data('company') || 'N/A';
            const clientPhone = selectedOption.data('phone') || 'N/A';
            const clientEmail = selectedOption.data('email') || 'N/A';

            if (clientId) {
                $('#client-details-table').show();
                $('#client-details-body').empty(); // Clear previous selection

                const clientRow = `
                    <tr id="client-row">
                        <td>${clientName}</td>
                        <td>${clientCompany}</td>
                        <td>${clientPhone}</td>
                        <td>${clientEmail}</td>
                    </tr>
                `;

                $('#client-details-body').append(clientRow);
            } else {
                $('#client-details-table').hide();
            }
        });
        
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
                // Check if the client was created successfully
                if (response.success) {
                    // Close the modal
                    $('#createClientModal').modal('hide');
                    
                    // Clear form inputs
                    $('#createClientForm')[0].reset();

                    // Append new client to the client select dropdown
                    $('#client').append(new Option(response.client.name, response.client.id));

                    // Re-initialize the select2 to refresh the dropdown
                    $('#client').trigger('change');

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
    // Initialize product table and searchable select
    let products = [];

    // Add product to the table
    $('#product').on('change', function() {
        const selectedOption = $(this).find(':selected');

        const productId = selectedOption.val();
    
        // Check if product is already in the table
        if ($('#product-table tbody tr[data-product-id="' + productId + '"]').length > 0) {
            toastr.error('This product is already added!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
            return;
        }

        const productName = selectedOption.data('name');
        const productPrice = parseFloat(selectedOption.data('price'));
        const productStock = parseInt(selectedOption.data('stock'));
        //const productId = selectedOption.val();

        const productRow = `
            <tr data-product-id="${productId}">
                <td class="col-3">${productName}</td>

                <td class="col-2">
                    <input type="number" class="price-input form-control" value="${productPrice.toFixed(2)}" step="1" data-product-id="${productId}" oninput="updateRow(this)">
                </td>
                
                <td class="col-1">
                    <input type="number" class="quantity form-control" value="1" min="1" data-price="${productPrice}" data-stock="${productStock}" oninput="updateRow(this)" />
                </td>

                <td class="current-stock col-2">
                    <span class="badge bg-info">${productStock}</span>
                </td>
                <td class="subtotal">${productPrice.toFixed(2)}</td>
                <td>
                    <input type="number" class="product-discount form-control" value="0" oninput="updateRow(this)" />
                </td>
                <td class="total">${productPrice.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;

        $('#product-table tbody').append(productRow);
        updateTotal();

        // Hide "No Product Found" row if there are products in the table
        $('#no-products-row').hide();

        // Reset product select
        $(this).val('');

        // Add the product to the hidden fields
        addToHiddenFields(productId, 1, productPrice);
    });

    // Function to add selected product to hidden fields
    function addToHiddenFields(productId, quantity, price) {
        let productIds = $('#product_ids').val() ? $('#product_ids').val().split(',') : [];
        let quantities = $('#quantities').val() ? $('#quantities').val().split(',') : [];
        //alert(quantities);
        let prices = $('#prices').val() ? $('#prices').val().split(',') : [];

        // Add product details to arrays
        //console.log("productId = ", productId);
        productIds.push(productId);
        //console.log("quantity = ", quantity);
        quantities.push(quantity);
        prices.push(price);

        // Update hidden fields with the new values
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));

        // // Debugging console logs
        // console.log("Updated product_ids:", $('#product_ids').val());
        // console.log("Updated quantities:", $('#quantities').val());
        // console.log("Updated prices:", $('#prices').val());
    }



    // Remove product from table and hidden fields
    $('#product-table').on('click', '.remove-product', function() {
        const row = $(this).closest('tr');
        const productId = row.find('input[type="number"]').data('product-id');
        const quantity = row.find('input[type="number"]').val();
        const price = row.find('.subtotal').text();

        // Remove product details from hidden fields
        removeFromHiddenFields(productId, quantity, price);

        // Remove the row from the table
        row.remove();

        // Show "No Product Found" row if table is empty
        if ($('#product-table tbody tr').length === 0) {
            $('#no-products-row').show();
        }

        updateTotal();
    });

    // Function to remove product from hidden fields
    function removeFromHiddenFields(productId, quantity, price) {
        let productIds = $('#product_ids').val().split(',');
        let quantities = $('#quantities').val().split(',');
        let prices = $('#prices').val().split(',');

        // Find the index of the product to remove
        const index = productIds.indexOf(productId);

        if (index !== -1) {
            productIds.splice(index, 1);
            quantities.splice(index, 1);
            prices.splice(index, 1);
        }

        // Update hidden fields with the new values
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));

        // Debugging console logs
        // console.log("After Removal - product_ids:", $('#product_ids').val());
        // console.log("After Removal - quantities:", $('#quantities').val());
        // console.log("After Removal - prices:", $('#prices').val());
    }

    


    // Update row subtotal when quantity changes
    function updateRow(input) {

        const row = $(input).closest('tr');
        const priceInput = row.find('.price-input');
        const quantityInput = row.find('.quantity');
        const discountInput = row.find('.product-discount');

        const price = parseFloat(priceInput.val());
        let quantity = parseInt(quantityInput.val());
        const stock = parseInt(quantityInput.data('stock'));
        const discount = parseFloat(discountInput.val());

        if (isNaN(price) || price < 0) {
            toastr.error('Invalid price entered.', 'Error', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });

            priceInput.val(0); // Reset to 0 if invalid input
            return;
        }

        // console.log("Updating row for product:", row.data('product-id'));
        // console.log("Price:", price, "Quantity:", quantity, "Stock:", stock);

        if (quantity > stock) {
            // Display toastr alert
            toastr.error('Quantity cannot exceed available stock.', 'Stock Limit Exceeded', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });

            $(input).val(stock);  // Reset to stock value
        }

        const subtotal = price * quantity;

        // Apply the product-specific discount
        const discountedTotal = subtotal - discount;

        row.find('.subtotal').text(subtotal.toFixed(2));
        row.find('.total').text(discountedTotal.toFixed(2));

        // Update the hidden fields
        updateHiddenFields();
         
        updateTotal();
    }

    // Function to update hidden fields when quantity changes
    function updateHiddenFields() {
        let productIds = [];
        let quantities = [];
        let prices = [];
        let discounts = [];

        $('#product-table tbody tr').each(function() {
            const row = $(this);
            const productId = row.data('product-id');  // Get product ID from <tr>
            const quantity = row.find('.quantity').val();
            const price = row.find('.price-input').val();
            const discount = row.find('.product-discount').val();

            // // Debugging logs
            // console.log("Row Data:", row.html());  // Log entire row structure
            // console.log("Extracted productId:", productId);
            // console.log("Extracted quantity:", quantity);
            // console.log("Extracted price:", price);

            // if (productId) {
            if (productId !== undefined) { // Ensure productId is valid
                productIds.push(productId);
                quantities.push(quantity);
                prices.push(price);
                discounts.push(discount);
            }
        });

        // Update the hidden fields
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));
        $('#discounts').val(discounts.join(','));

        // // Debugging logs
        // console.log("Updated product_ids:", $('#product_ids').val());
        // console.log("Updated quantities:", $('#quantities').val());
        // console.log("Updated prices:", $('#prices').val());
    }



    // Calculate the subtotal, discount, and total
    function updateTotal() {
        let subtotal = 0;

        $('#product-table tbody tr').each(function() {
            const rowSubtotal = parseFloat($(this).find('.total').text());
            if (!isNaN(rowSubtotal)) {
                subtotal += rowSubtotal;
            }
        });

        // Get discount and handle invalid input
        const discount = parseFloat($('#discount').val());
        const validDiscount = isNaN(discount) ? 0 : discount;

        const total = subtotal - validDiscount;

        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val(total.toFixed(2));
    }
</script>
@endpush
