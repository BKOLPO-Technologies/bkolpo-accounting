@extends('layouts.admin', ['pageTitle' => 'Purchase'])
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
                                            <option value="">Select Client</option>
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

                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $sale->invoice_no) }}" readonly />

                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="invoice_date">Invoice Date</label>

                                    <input type="date" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', $sale->invoice_date->format('Y-m-d')) }}" readonly />

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
                                                @if ($sale->products->isEmpty())
                                                <tr id="no-products-row">
                                                    <td colspan="5" class="text-center">No product found</td>
                                                </tr>
                                                @else
                                                @foreach ($sale->products as $product)
                                                        <input type="hidden" name="product_multiple_id[]" value="{{ $product->id }}">
                                                        <tr data-product-id="{{ $product->id }}">
                                                            <td class="col-3">{{ $product->name }}</td>
                                                            <td class="col-2">
                                                                <input type="number" class="price-input form-control" value="{{ number_format($product->pivot->price, 2) }}" step="1" data-product-id="{{ $product->id }}" oninput="updateRow(this)">
                                                            </td>
                                                            <td class="col-1">
                                                                <input type="number" class="quantity form-control" value="{{ $product->pivot->quantity }}" min="1" data-price="{{ $product->price }}" data-stock="{{ $product->quantity ?? 0 }}" oninput="updateRow(this)" />
                                                            </td>
                                                            <td class="current-stock col-2">
                                                                <span class="badge bg-info">{{ $product->quantity }}</span>
                                                            </td>
                                                            <td class="subtotal">{{ number_format($product->pivot->quantity * $product->price, 2) }}</td>
                                                            <td>
                                                                <input type="number" class="product-discount form-control" value="{{ number_format($product->pivot->discount, 2) }}" step="0.01" oninput="updateRow(this)" />
                                                            </td>
                                                            <td class="total">{{ number_format($product->pivot->quantity * $product->price, 2) }}</td>
                                                            <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
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
                                    <input type="text" id="discount" name="discount" class="form-control" value="{{ old('discount', $sale->discount ?? 0) }}" oninput="updateTotal()" />
                                </div>

                                <!-- Total -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="total">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" value="{{ old('total', $subtotal - ($sale->discount ?? 0)) }}" readonly />
                                </div>
                            </div>
                            <hr>
                            <!-- Description -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the description">{{ $sale->description }}</textarea>
                            </div>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>Update Sale</button>
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
<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createClientModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Client
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form id="createClientForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="new_client_name">Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_name" name="name" placeholder="Enter Client Name" required>
                                </div>
                            </div>
                            <!-- Company -->
                            <div class="form-group">
                                <label for="new_client_company">Company</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_company" placeholder="Enter Company Name" name="company">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="new_client_phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_phone" placeholder="Enter Phone No" name="phone">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="new_client_email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="new_client_email" placeholder="Enter Client Email"  name="email">
                                </div>
                            </div>
                            <!-- Address -->
                            <div class="form-group">
                                <label for="new_client_address">Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_address" placeholder="Enter Address" name="address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- City -->
                            <div class="form-group">
                                <label for="new_client_city">City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_city" placeholder="Enter City" name="city">
                                </div>
                            </div>
                            <!-- Region -->
                            <div class="form-group">
                                <label for="new_client_region">Region</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_region" placeholder="Enter Region" name="region">
                                </div>
                            </div>
                            <!-- Country -->
                            <div class="form-group">
                                <label for="new_client_country">Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_country" placeholder="Enter Country" name="country">
                                </div>
                            </div>
                            <!-- Post Box -->
                            <div class="form-group">
                                <label for="new_client_postbox">Post Box</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_postbox" placeholder="Enter Post Box" name="postbox">
                                </div>
                            </div>
                            <!-- TAX ID -->
                            <div class="form-group">
                                <label for="new_client_taxid">TAX ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_taxid" placeholder="Enter Transaction ID" name="taxid">
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    // Initialize product table and handle adding/removing products
    let products = [];

    // Add product to the table when a product is selected
    $('#product').on('change', function () {
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
        let prices = $('#prices').val() ? $('#prices').val().split(',') : [];

        // Add product details to arrays
        productIds.push(productId);
        quantities.push(quantity);
        prices.push(price);

        // Update hidden fields with the new values
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));
    }

    // Update row subtotal, discount, and total when quantity, price, or discount changes
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

        if (quantity > stock) {
            // Display toastr alert
            toastr.error('Quantity cannot exceed available stock.', 'Stock Limit Exceeded', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });

            $(input).val(stock);  // Reset to stock value
        }

        // Calculate subtotal (before discount) for this product
        const subtotal = price * quantity;

        // Apply the product-specific discount
        const discountedTotal = subtotal - discount;

        // Update row subtotal and discounted total
        row.find('.subtotal').text(subtotal.toFixed(2));
        row.find('.total').text(discountedTotal.toFixed(2));

        // Update hidden fields
        updateHiddenFields();
        updateTotal();  // Update total after any change
    }

    // Function to update hidden fields when quantity changes
    function updateHiddenFields() {
        let productIds = [];
        let quantities = [];
        let prices = [];
        let discounts = [];

        $('#product-table tbody tr').each(function () {
            const row = $(this);
            const productId = row.data('product-id');
            const quantity = row.find('.quantity').val();
            const price = row.find('.price-input').val();
            const discount = row.find('.product-discount').val();

            if (productId !== undefined) {
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
    }

    // Calculate the subtotal for all products, apply flat discount to the subtotal, and calculate final total
    function updateTotal() {
        let subtotal = 0;

        $('#product-table tbody tr').each(function () {
            const rowSubtotal = parseFloat($(this).find('.total').text()); // Use the total (after product discount)
            if (!isNaN(rowSubtotal)) {
                subtotal += rowSubtotal;
            }
        });

        // Get the flat discount amount (order-wide discount)
        const discount = parseFloat($('#discount').val());
        const validDiscount = isNaN(discount) ? 0 : discount;

        // Calculate the final total with the flat discount applied
        const total = subtotal - validDiscount;

        // Update subtotal and total fields
        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val(total.toFixed(2));
    }

    // Remove product from table and hidden fields
    $('#product-table').on('click', '.remove-product', function () {
        const row = $(this).closest('tr');
        const productId = row.data('product-id');
        const quantity = row.find('.quantity').val();
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
    }
</script>
@endpush
