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
                            <a href="{{ route('admin.purchase.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.purchase.store') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="product_ids" id="product_ids">
                            <input type="hidden" name="quantities" id="quantities">
                            <input type="hidden" name="prices" id="prices">
                            <input type="hidden" name="discounts" id="discounts">

                            <div class="row">
                                <!-- Supplier Select -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="supplier">Supplier</label>
                                    <div class="input-group">
                                        <select name="supplier" id="supplier" class="form-control select2 @error('supplier') is-invalid @enderror">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <!-- <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option> -->

                                                <option value="{{ $supplier->id }}" 
                                                    data-name="{{ $supplier->name }}" 
                                                    data-company="{{ $supplier->company }}" 
                                                    data-phone="{{ $supplier->phone }}" 
                                                    data-email="{{ $supplier->email }}"
                                                    {{ old('supplier') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" id="addSupplierBtn" data-toggle="modal" data-target="#createSupplierModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('supplier')
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
                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $invoice_no) }}" readonly />
                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="invoice_date">Invoice Date</label>
                                    <input type="date" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" readonly />
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
                                        <table class="table table-bordered" id="supplier-details-table" style="display: none;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Company</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody id="supplier-details-body"></tbody>
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
                                                <tr id="no-products-row">
                                                    <td colspan="8" class="text-center">No product found</td>
                                                </tr>
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
                                    <input type="text" id="subtotal" name="subtotal" class="form-control" value="0" readonly />
                                </div>

                                <!-- Discount -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="discount">Discount</label>
                                    <input type="text" id="discount" name="discount" class="form-control" value="0" oninput="updateTotal()" />
                                </div>

                                <!-- Total -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="total">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" value="0" readonly />
                                </div>
                            </div><hr>
                            <!-- Description -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the description"></textarea>
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

<!-- Modal for creating a new supplier -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Supplier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createSupplierForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="new_supplier_name">Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_name" name="name" required>
                                </div>
                            </div>
                            <!-- Company -->
                            <div class="form-group">
                                <label for="new_supplier_company">Company</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_company" name="company">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="new_supplier_phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_phone" name="phone">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="new_supplier_email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="new_supplier_email" name="email">
                                </div>
                            </div>
                            <!-- Address -->
                            <div class="form-group">
                                <label for="new_supplier_address">Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_address" name="address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- City -->
                            <div class="form-group">
                                <label for="new_supplier_city">City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_city" name="city">
                                </div>
                            </div>
                            <!-- Region -->
                            <div class="form-group">
                                <label for="new_supplier_region">Region</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_region" name="region">
                                </div>
                            </div>
                            <!-- Country -->
                            <div class="form-group">
                                <label for="new_supplier_country">Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_country" name="country">
                                </div>
                            </div>
                            <!-- Post Box -->
                            <div class="form-group">
                                <label for="new_supplier_postbox">Post Box</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_postbox" name="postbox">
                                </div>
                            </div>
                            <!-- TAX ID -->
                            <div class="form-group">
                                <label for="new_supplier_taxid">TAX ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_taxid" name="taxid">
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

        // Supplier selection event
        $('#supplier').on('change', function () {
            const selectedOption = $(this).find(':selected');
            const supplierId = selectedOption.val();
            const supplierName = selectedOption.data('name') || 'N/A';
            const supplierCompany = selectedOption.data('company') || 'N/A';
            const supplierPhone = selectedOption.data('phone') || 'N/A';
            const supplierEmail = selectedOption.data('email') || 'N/A';

            if (supplierId) {
                $('#supplier-details-table').show();
                $('#supplier-details-body').empty(); // Clear previous selection

                const supplierRow = `
                    <tr id="supplier-row">
                        <td>${supplierName}</td>
                        <td>${supplierCompany}</td>
                        <td>${supplierPhone}</td>
                        <td>${supplierEmail}</td>
                    </tr>
                `;

                $('#supplier-details-body').append(supplierRow);
            } else {
                $('#supplier-details-table').hide();
            }
        });
        
    });
</script>

<script> 
    $('#createSupplierForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = $(this).serialize(); // Get form data

        $.ajax({
            url: '{{ route('admin.supplier2.store') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Check if the supplier was created successfully
                if (response.success) {
                    // Close the modal
                    $('#createSupplierModal').modal('hide');
                    
                    // Clear form inputs
                    $('#createSupplierForm')[0].reset();

                    // Append new supplier to the supplier select dropdown
                    $('#supplier').append(new Option(response.supplier.name, response.supplier.id));

                    // Re-initialize the select2 to refresh the dropdown
                    $('#supplier').trigger('change');

                    // Show success message
                    toastr.success('Supplier added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                // Handle error (validation errors, etc.)
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_supplier_${field}`).addClass('is-invalid');
                    $(`#new_supplier_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
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
            alert('This product is already added!');
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
                
                <td class="discount-col">
                    <input type="number" class="product-discount form-control" value="0" min="0" max="100" oninput="updateRow(this)" placeholder="Enter discount">
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
        // const row = $(input).closest('tr');
        // const price = parseFloat($(input).data('price'));
        // const quantity = parseInt($(input).val());
        // const stock = parseInt($(input).data('stock'));

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
