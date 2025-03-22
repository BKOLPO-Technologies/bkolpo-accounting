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
                        <form method="POST" action="{{ route('admin.purchase.update', $purchase->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="product_ids" id="product_ids">
                            <input type="hidden" name="quantities" id="quantities">
                            <input type="hidden" name="prices" id="prices">
                            <input type="hidden" name="discounts" id="discounts">

                            <div class="row">
                                <!-- Supplier Select -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="supplier">Supplier</label>
                                    <div class="input-group">
                                        <!-- --- -->
                                        <select name="supplier" id="supplier" class="form-control select2 @error('supplier') is-invalid @enderror">
                                            <option value="" disabled>Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" 
                                                    data-name="{{ $supplier->name }}" 
                                                    data-company="{{ $supplier->company }}" 
                                                    data-phone="{{ $supplier->phone }}" 
                                                    data-email="{{ $supplier->email }}"
                                                    {{ (old('supplier', $purchase->supplier_id ?? '') == $supplier->id) ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- --- -->
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

                                <!-- Category Select with Search Feature -->
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label for="category_id">Category</label>
                                    <div class="input-group">
                                        <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="all">All Categories</option> <!-- Option to select all categories -->
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Product Select with Search Feature -->
                                <div class="col-lg-2 col-md-6 mb-3">
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

                                <!-- Project Select with Search Feature -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="project_id">Project</label>
                                    <div class="input-group">
                                        <select name="project_id" id="project_id" class="form-control select2 @error('project_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" {{ $project->id == $purchase->project_id ? 'selected' : '' }}>
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
                                <div class="col-lg-2 col-md-6 mb-3">
                                    <label for="invoice_no">Invoice No</label>

                                    <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no', $purchase->invoice_no) }}" readonly />

                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Invoice Date -->
                                {{-- <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="invoice_date">Invoice Date</label>

                                    <input type="date" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', $purchase->invoice_date->format('Y-m-d')) }}" readonly />

                                    @error('invoice_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div> --}}
                            </div>

                            <!-- Supplier Details Table -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <!-- <table class="table table-bordered" id="supplier-details-table" style="display: none;"> -->
                                    <table class="table table-bordered" id="supplier-details-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplier-details-body">
                                            <td>{{ $purchase->supplier->name }}</td>
                                            <td>{{ $purchase->supplier->company }}</td>
                                            <td>{{ $purchase->supplier->phone }}</td>
                                            <td>{{ $purchase->supplier->email }}</td>
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
                                            @if ($purchase->products->isEmpty())
                                                <tr id="no-products-row">
                                                    <td colspan="6" class="text-center">No product found</td>
                                                </tr>
                                            @else
                                            @foreach ($purchase->products as $product)
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td class="col-3">{{ $product->name }}</td>
                                                    
                                                    <td class="col-2">
                                                        <input type="number" class="price-input form-control" value="{{ $product->pivot->price }}" step="1" 
                                                            data-product-id="{{ $product->id }}" oninput="updateRow(this)">
                                                    </td>

                                                    <td class="col-1">
                                                        <input type="number" class="quantity form-control" value="{{ $product->pivot->quantity }}" min="1"
                                                            data-price="{{ $product->price }}" data-stock="{{ $product->quantity ?? 0 }}" oninput="updateRow(this)" />
                                                    </td>

                                                    <td class="current-stock col-2">
                                                        <span class="badge bg-info">{{ $product->quantity }}</span>
                                                    </td>

                                                    <td class="subtotal">
                                                        {{ number_format($product->pivot->quantity * $product->price, 2) }}
                                                    </td>

                                                    <td>
                                                        <input type="number" class="product-discount form-control" value="{{ $product->pivot->discount }}" step="1" oninput="updateRow(this)" />
                                                    </td>

                                                    {{-- <td class="total">{{ number_format((($product->pivot->quantity * $product->price) - $product->pivot->discount), 2) }}</td> --}}
                                                    <td class="total">{{ (($product->pivot->quantity * $product->price) - $product->pivot->discount) }}</td>

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
                                
                                {{-- <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="subtotal">Subtotal</label>
                                    <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ old('subtotal', $subtotal) }}" readonly />
                                </div>

                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="discount">Discount</label>
                                    <input type="number" min="0" id="discount" name="discount" class="form-control" value="{{ old('discount', $purchase->discount ?? 0) }}" oninput="updateTotal()" />
                                </div>

                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="total">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" value="{{ old('total', $subtotal - ($purchase->discount ?? 0)) }}" readonly />
                                </div> --}}

                                <div class="col-12 col-lg-4 mb-2">
                                    <div class="row w-100">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="subtotal">Subtotal</label>
                                            <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ old('subtotal', $subtotal) }}" readonly />
                                        </div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="total_discount">Total Discount</label>
                                            <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $purchase->discount ?? 0) }}" oninput="updateTotalVatTax()" />
                                        </div>
                                    </div>

                                    <div class="row w-100">
                                        
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="transport_cost">Transport Cost</label>
                                            <input type="number" min="0" id="transport_cost" name="transport_cost" class="form-control" placeholder="Enter Transport Cost" value="{{ old('transport_cost', $purchase->transport_cost ?? 0) }}" oninput="updateTotalVatTax()"/>
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="carrying_charge">Carrying Charge</label>
                                            <input type="number" min="0" id="carrying_charge" name="carrying_charge" class="form-control" placeholder="Enter Carrying Charge" value="{{ old('carrying_charge', $purchase->carrying_charge ?? 0) }}" oninput="updateTotalVatTax()" />
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="vat">Vat</label>
                                            <input type="number" min="0" id="vat" name="vat" class="form-control" placeholder="Enter Vat" value="{{ old('vat', $purchase->vat ?? 0) }}" oninput="updateTotalVatTax()"/>
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-3">
                                            <label for="tax">Tax</label>
                                            <input type="number" min="0" id="tax" name="tax" class="form-control" placeholder="Enter Tax" value="{{ old('tax', $purchase->tax ?? 0) }}" oninput="updateTotalVatTax()"/>
                                        </div>
                                        
                                        <div class="col-12 mb-2">
                                            <label for="grand_total">Grand Total</label>
                                            <input type="number" min="0" id="total" name="total" class="form-control" value="{{ old('total', $subtotal - ($purchase->discount ?? 0) + ($purchase->transport_cost ?? 0) + ($purchase->carrying_charge ?? 0) + ($purchase->vat ?? 0) + ($purchase->tax ?? 0)) }}" readonly />
                                        </div>
                                    </div>

                                </div>

                            </div>
                            
                            <hr>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>Update Purchase</button>
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
@include('backend.admin.supplier.supplier_modal')

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
    // When a category is selected
    $('#category_id').on('change', function() {
        var categoryId = $(this).val();
        loadProductsByCategory(categoryId);  // Load products for the selected category
    });

    // Initialize product table and searchable select
    let products = [];

    // Add product to the table
    $('#product').on('change', function() {
        const selectedOption = $(this).find(':selected');

        const productId = selectedOption.val();

        if (!productId) {
            return; // Do nothing if no product is selected
        }
    
        // Check if product is already in the table
        if ($('#product-table tbody tr[data-product-id="' + productId + '"]').length > 0) {
            toastr.error('This product is already added!.', {
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

                <td  class="col-1">
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

    // Function to load products based on the selected category
    function loadProductsByCategory(categoryId) {
        var $productSelect = $('#product');

        // Clear the current product options and show loading message
        $productSelect.empty();
        $productSelect.append('<option value="">Loading products...</option>');

        // Send an AJAX request to fetch the products for the selected category
        $.ajax({
            url: '/admin/product/products-by-category/' + categoryId,
            method: 'GET',
            success: function(response) {
                // Empty the select element and add the default "Select Product" option
                $productSelect.empty();
                $productSelect.append('<option value="">Select Product</option>');

                if (response.length > 0) {
                    // Append products to the select dropdown
                    response.forEach(function(product) {
                        $productSelect.append('<option value="' + product.id + '" data-id="' + product.id + '" data-name="' + product.name + '" data-price="' + product.price + '" data-stock="' + product.quantity + '">' + product.name + '</option>');
                    });
                } else {
                    $productSelect.append('<option value="">No products found</option>');
                }

                // Refresh select2 after updating the options
                $productSelect.trigger('change');
            },
            error: function() {
                $productSelect.empty();
                $productSelect.append('<option value="">Error fetching products</option>');
            }
        });
    }

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

    function updateTotalVatTax() {
        let subtotal = 0;

        // Loop through each product row and sum up the totals
        $('#product-table tbody tr').each(function () {
            const rowTotal = parseFloat($(this).find('.total').text()) || 0;
            subtotal += rowTotal;
        });

        // Get other input values, ensuring they are numeric
        let discount = parseFloat($('#discount').val()) || 0;
        let transportCost = parseFloat($('#transport_cost').val()) || 0;
        let carryingCharge = parseFloat($('#carrying_charge').val()) || 0;
        let vat = parseFloat($('#vat').val()) || 0;
        let tax = parseFloat($('#tax').val()) || 0;

        // Calculate the grand total
        let total = subtotal - discount + transportCost + carryingCharge + vat + tax;

        // Update input fields
        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val(total.toFixed(2));

        updateHiddenFields();
    }

    // Calculate the subtotal, discount, and total
    function updateTotal() {
        let subtotal = 0;
        //let subtotal = parseFloat($('#subtotal').val()) || 0;

        $('#product-table tbody tr').each(function() {
            //const rowSubtotal = parseFloat($(this).find('.subtotal').text());
            const rowSubtotal = parseFloat($(this).find('.total').text());
            console.log(rowSubtotal);
            if (!isNaN(rowSubtotal)) {
                subtotal += rowSubtotal;
            }
        });

        // Get discount and handle invalid input
        const discount = parseFloat($('#discount').val());
        const validDiscount = isNaN(discount) ? 0 : discount;
        
        $('#subtotal').val(subtotal.toFixed(2));
        //const total = subtotal - validDiscount;

        let transportCost = parseFloat($('#transport_cost').val()) || 0;
        let carryingCharge = parseFloat($('#carrying_charge').val()) || 0;
        let vat = parseFloat($('#vat').val()) || 0;
        let tax = parseFloat($('#tax').val()) || 0;

        // // Calculate grand total
        let total = subtotal - validDiscount + transportCost + carryingCharge + vat + tax;
        
        $('#total').val(total.toFixed(2));
    }
</script>
@endpush