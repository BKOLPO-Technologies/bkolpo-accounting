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
                                {{--
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
                                --}}

                                <!-- Project Select with Search Feature -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="project_id">Project</label>
                                    <div class="input-group">
                                        <select name="project_id" id="project_id" class="form-control select2 @error('project_id') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" 
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
                                                    <th>Category</th>
                                                    <th>Item Description</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Subtotal</th>
                                                    <th>Discount</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-tbody">
                                                @foreach ($sale->saleProducts as $product)
                                                <tr>
                                                    <td style="width:15% !important;">
                                                        <select name="category_id" id="category_id" class="form-control select2 category-select @error('category_id') is-invalid @enderror" style="width: 100%;">
                                                            <option value="all">All Categories</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ (old('category_id') ?? $product->product->category_id) == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td style="width:17% !important;">
                                                        <select name="products" id="product" class="form-control select2 @error('products') is-invalid @enderror product-select" style="width: 100%;">
                                                            <option value="">Select Product</option>
                                                            @foreach($aproducts as $aproduct)
                                                                <option value="{{ $aproduct->id }}" data-category="{{ $aproduct->category_id }}" data-id="{{ $aproduct->id }}" data-name="{{ $aproduct->name }}" data-price="{{ $aproduct->price }}" data-unit="{{ $aproduct->unit->name }}"
                                                                    {{ (old('aproduct_id') ?? $product->product_id) == $aproduct->id ? 'selected' : '' }}>
                                                                    {{ $aproduct->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="unit_price[]" class="form-control unit-price" readonly value="{{ $product->price }}">
                                                    </td>
                                                    <td style="width:11% !important;">
                                                        <input type="number" name="quantity[]" class="form-control quantity" min="1" value="{{ $product->quantity }}" required>
                                                    </td>
                                                    <td style="width:7% !important;">
                                                        <input type="text" name="order_unit[]" class="form-control unit-input" value="{{ $product->product->unit->name }}" required readonly>
                                                    </td>
                                                    <td style="width:8% !important;"> 
                                                        <input type="text" name="subtotal[]" class="form-control subtotal" value="{{ $product->price * $product->quantity }}" readonly>
                                                    </td>
                                                    <td style="width:11% !important;">
                                                        <input type="number" name="discount[]" class="form-control product-discount" min="0" step="0.01" value="{{ $product->discount }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total[]" class="form-control total" readonly value="{{ ((($product->price) * ($product->quantity)) - ($product->discount)) }}">
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($loop->first)
                                                            <button type="button" class="btn btn-success btn-sm add-row"><i class="fas fa-plus"></i></button>
                                                        @else
                                                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end flex-column align-items-end">
                                
                                <div class="col-12 col-lg-4 mb-2">
                                    <div class="row w-100">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="subtotal">Subtotal</label>
                                            <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ $subtotal }}" readonly />
                                        </div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="total_discount">Total Discount</label>
                                            <input type="number" id="discount" name="discount" class="form-control" value="{{ $sale->discount }}" step="0.01" oninput="updateTotal()" />
                                        </div>
                                    </div>

                                    <div class="row w-100">
                                        
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="transport_cost">Transport Cost</label>
                                            <input type="number" min="0" id="transport_cost" name="transport_cost" class="form-control" step="0.01" value="{{ $sale->transport_cost }}" oninput="updateTotal()"/>
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="carrying_charge">Carrying Charge</label>
                                            <input type="number" min="0" id="carrying_charge" name="carrying_charge" class="form-control" step="0.01"  value="{{ $sale->carrying_charge }}" oninput="updateTotal()" />
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-2">
                                            <label for="vat">VAT</label>
                                            <input type="number" min="0" id="vat" name="vat" class="form-control" value="{{ $sale->vat }}" step="0.01" oninput="updateTotal()"/>
                                        </div>
                                
                                        <div class="col-12 col-lg-6 mb-3">
                                            <label for="tax">Tax</label>
                                            <input type="number" min="0" id="tax" name="tax" class="form-control" value="{{ $sale->tax }}" step="0.01" oninput="updateTotal()"/>
                                        </div>
                                        
                                        <div class="col-12 mb-2">
                                            <label for="grand_total">Grand Total</label>
                                            <input type="number" min="0" id="total" name="total" class="form-control" value="{{ $grandtotal }}" readonly />
                                        </div>
                                    </div>

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
    $(document).ready(function () {

        function addToHiddenFields(productId, quantity, price, discount) {
            let productIds = $('#product_ids').val() ? $('#product_ids').val().split(',') : [];
            let quantities = $('#quantities').val() ? $('#quantities').val().split(',') : [];
            let prices = $('#prices').val() ? $('#prices').val().split(',') : [];
            let discounts = $('#discounts').val() ? $('#discounts').val().split(',') : [];

            let index = productIds.indexOf(productId); 

            if (index !== -1) {
                
                quantities[index] = quantity;
                prices[index] = price;
                discounts[index] = discount;
            } else {
                
                productIds.push(productId);
                quantities.push(quantity);
                prices.push(price);
                discounts.push(discount);
            }

            $('#product_ids').val(productIds.join(','));
            $('#quantities').val(quantities.join(','));
            $('#prices').val(prices.join(','));
            $('#discounts').val(discounts.join(','));
        }

        function removeFromHiddenFields(productId) {
            let productIds = $('#product_ids').val().split(',');
            let quantities = $('#quantities').val().split(',');
            let prices = $('#prices').val().split(',');
            let discounts = $('#discounts').val().split(',');

            let index = productIds.indexOf(productId);
            if (index !== -1) {
                productIds.splice(index, 1);
                quantities.splice(index, 1);
                prices.splice(index, 1);
                discounts.splice(index, 1);
            }

            $('#product_ids').val(productIds.join(','));
            $('#quantities').val(quantities.join(','));
            $('#prices').val(prices.join(','));
            $('#discounts').val(discounts.join(','));
        }

        function loadProductsByCategory(categoryId, row) {
            var $productSelect = row.find('.product-select'); 

            if (!categoryId) {
                $productSelect.empty().append('<option value="">Select a category first</option>');
                return;
            }

            $productSelect.empty().append('<option value="">Loading products...</option>');

            $.ajax({
                url: '/admin/product/products-by-category/' + encodeURIComponent(categoryId), 
                method: 'GET',
                dataType: 'json', 
                success: function(response) {

                    $productSelect.empty().append('<option value="">Select Product</option>');

                    if (Array.isArray(response) && response.length > 0) {
                        
                        response.forEach(function(product) {
                            let unitName = product.unit && product.unit.name ? product.unit.name : 'N/A'; 

                            $productSelect.append(`
                                <option value="${product.id}" 
                                        data-id="${product.id}" 
                                        data-name="${product.name}" 
                                        data-price="${product.price}" 
                                        data-unit="${unitName}">
                                    ${product.name}
                                </option>
                            `);
                        });
                    } else {
                        $productSelect.append('<option value="">No products found</option>');
                    }

                    $productSelect.trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $productSelect.empty().append('<option value="">Error fetching products</option>');
                }
            });
        }

        $(document).on('change', '.category-select', function() {
            var categoryId = $(this).val();
            var row = $(this).closest('tr'); 
            loadProductsByCategory(categoryId, row);  
        });

        $(document).on('change', '.product-select', function () {
            let selectedOption = $(this).find(':selected');
            let productId = selectedOption.val();
            let productPrice = selectedOption.data('price') || 0;
            let productUnit = selectedOption.data('unit') || '';

            let isDuplicate = false;
            $('.product-select').not(this).each(function () {
                if ($(this).val() === productId) {
                    isDuplicate = true;
                    return false;
                }
            });

            if (isDuplicate) {
                toastr.error('This product is already added!', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000
                });
                $(this).val('').trigger('change'); 
                return;
            }

            if (productId) {
                let row = $(this).closest('tr');

                row.find('.unit-price').val(productPrice);
                row.find('.quantity').val(1);
                row.find('.subtotal').val(productPrice);
                row.find('.total').val(productPrice);
                row.find('.unit-input').val(productUnit);
                row.find('.product-discount').val(0);

                addToHiddenFields(productId, 1, productPrice, 0);

            } else {
                console.log("productId not found");
            }

            calculateTotal();

        });

        $(document).on('input', '.quantity, .product-discount', function () {
            let row = $(this).closest('tr');
            let productId = row.find('.product-select').val();
            let unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
            let quantity = parseFloat(row.find('.quantity').val()) || 1;
            let discount = parseFloat(row.find('.product-discount').val()) || 0;

            let subtotal = unitPrice * quantity;
            let total = subtotal - discount;

            row.find('.subtotal').val(subtotal.toFixed(2));
            row.find('.total').val(total.toFixed(2));

            addToHiddenFields(productId, quantity, unitPrice, discount);

            calculateTotal();
        });

        function calculateTotal() {

            let subtotal = 0;
            let totalDiscount = parseFloat($("#discount").val()) || 0;
            let transportCost = parseFloat($("#transport_cost").val()) || 0;
            let carryingCharge = parseFloat($("#carrying_charge").val()) || 0;
            let vat = parseFloat($("#vat").val()) || 0;
            let tax = parseFloat($("#tax").val()) || 0;

            $(".total").each(function () {
                subtotal += parseFloat($(this).val()) || 0;
            });

            let grandTotal = subtotal - totalDiscount + transportCost + carryingCharge + vat + tax;

            $("#subtotal").val(subtotal.toFixed(2));
            $("#total").val(grandTotal.toFixed(2));
        }

        $(document).on("input", "#discount, #transport_cost, #carrying_charge, #vat, #tax", function () {
            calculateTotal();
        });

        $(document).on('click', '.add-row', function () {
            let newRow = `
                <tr>
                    <td>
                        <select name="category_id" id="category_id" class="form-control category-select select2 @error('category_id') is-invalid @enderror" style="width: 100%;">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="aproducts[]" class="form-control select2 product-select" style="width: 100%;">
                            <option value="">Select Product</option>
                            @foreach($aproducts as $aproduct)
                                <option value="{{ $aproduct->id }}" data-price="{{ $aproduct->price }}" data-unit="{{ $aproduct->unit->name }}">
                                    {{ $aproduct->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" min="0" required readonly></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" min="1" value="1" required></td>
                    <td><input type="text" name="order_unit[]" class="form-control unit-input" required readonly></td>
                    <td><input type="text" name="subtotal[]" class="form-control subtotal" readonly></td>
                    <td><input type="number" name="discount[]" class="form-control product-discount" min="0" placeholder="Enter Discount"></td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            $('#product-tbody').append(newRow);

            $('.select2').select2();
        });

        $(document).on('click', '.remove-row', function () {

            let row = $(this).closest('tr');
            let productId = row.find('.product-select').val();

            row.remove(); 
            removeFromHiddenFields(productId); 
            calculateTotal(); 
        });
    });
</script>

@endpush
