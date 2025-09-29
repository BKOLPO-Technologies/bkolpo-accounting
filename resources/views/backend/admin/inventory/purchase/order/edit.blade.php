{{-- with vat tax checkable option --}}
@extends('layouts.admin', ['pageTitle' => 'Purchase Order'])
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
                                <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                <a href="{{ route('admin.purchase.order.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.purchase.order.update', $purchase->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="product_ids" id="product_ids" value="{{ $product_ids }}">
                                <input type="hidden" name="quantities" id="quantities" value="{{ $quantities }}">
                                <input type="hidden" name="prices" id="prices" value="{{ $prices }}">
                                <input type="hidden" name="discounts" id="discounts" value="{{ $discounts }}">


                                <div class="row">

                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="supplier">Vendor</label>
                                        <div class="input-group">
                                            <select name="supplier" id="supplier"
                                                class="form-control select2 @error('supplier') is-invalid @enderror">
                                                <option value="" disabled>Select Vendor</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                                        data-company="{{ $supplier->company }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-email="{{ $supplier->email }}"
                                                        {{ (old('supplier') ?? $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" id="addSupplierBtn"
                                                    data-toggle="modal" data-target="#createSupplierModal">
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

                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="project_id">Project</label>
                                        <div class="input-group">
                                            <select name="project_id" id="project_id"
                                                class="form-control select2 @error('project_id') is-invalid @enderror"
                                                style="width: 100%;">
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        {{ (old('project_id') ?? $purchase->project_id) == $project->id ? 'selected' : '' }}>
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

                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="invoice_no">PO No</label>
                                        <input type="text" id="invoice_no" name="invoice_no"
                                            class="form-control @error('invoice_no') is-invalid @enderror"
                                            value="{{ old('invoice_no', $purchase->invoice_no) }}" readonly />
                                        @error('invoice_no')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <table class="table table-bordered" id="supplier-details-table"
                                            style="display: none;">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Company Name</th>
                                                    <th>Group Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody id="supplier-details-body"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="product-table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Item</th>
                                                        <th>Speciphication</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        {{-- <th>Subtotal</th>
                                                    <th>Discount</th> --}}
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-tbody">
                                                    @foreach ($purchase->purchaseProducts as $product)
                                                        <tr>
                                                            <td style="width:14% !important;">
                                                                <select name="category_id" id="category_id"
                                                                    class="form-control select2 category-select @error('category_id') is-invalid @enderror"
                                                                    style="width: 100%;">
                                                                    <option value="all">All Categories</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}"
                                                                            {{ (old('category_id') ?? $product->product->category_id) == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="width:20% !important;">
                                                                <select name="aproducts[]" id="product"
                                                                    class="form-control select2 @error('products') is-invalid @enderror product-select"
                                                                    style="width: 100%;">
                                                                    <option value="">Select Product</option>
                                                                    @foreach ($aproducts as $aproduct)
                                                                        <option value="{{ $aproduct->id }}"
                                                                            data-category="{{ $aproduct->category_id }}"
                                                                            data-id="{{ $aproduct->id }}"
                                                                            data-name="{{ $aproduct->name }}"
                                                                            data-price="{{ $aproduct->price }}"
                                                                            data-unit="{{ $aproduct->unit->name }}"
                                                                            data-speciphiction="{{ $aproduct->description }}"
                                                                            {{ (old('aproduct_id') ?? $product->product_id) == $aproduct->id ? 'selected' : '' }}>
                                                                            {{ $aproduct->name }}{{ $aproduct->product_code ? ' (' . $aproduct->product_code . ')' : '' }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            </td>
                                                            <td style="width:14% !important;">
                                                                <input type="text" name="speciphictions[]"
                                                                    class="form-control speciphictions" readonly
                                                                    value="{{ $product->product->description }}">
                                                            </td>
                                                            <td style="width:14% !important;">
                                                                <input type="text" name="order_unit[]"
                                                                    class="form-control unit-input"
                                                                    value="{{ $product->product->unit->name }}" required
                                                                    readonly>
                                                            </td>
                                                            <td style="width:14% !important;">
                                                                <input type="number" name="unit_price[]"
                                                                    class="form-control unit-price" step="0.01"
                                                                    value="{{ $product->price }}"
                                                                    style="text-align: right;">
                                                            </td>
                                                            <td style="width:11% !important;">
                                                                <input type="number" name="quantity[]"
                                                                    class="form-control quantity" min="1"
                                                                    value="{{ $product->quantity }}" required>
                                                            </td>
                                                            <td style="width:14% !important;">
                                                                <input type="text" name="total[]"
                                                                    class="form-control subtotal"
                                                                    value="{{ $product->price * $product->quantity }}"
                                                                    readonly style="text-align: right;">
                                                            </td>
                                                            <td class="text-center" style="width:8% !important;">
                                                                @if ($loop->first)
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm add-row"><i
                                                                            class="fas fa-plus"></i></button>
                                                                @else
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm remove-row"><i
                                                                            class="fas fa-trash"></i></button>
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

                                    <div class="row w-100">
                                        <div class="col-12 col-lg-6 mb-2"></div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                    <tr>
                                                        <td><label for="subtotal">Total Amount</label></td>
                                                        <td class="text-end">
                                                            <input type="text" id="subtotal" name="subtotal"
                                                                class="form-control" value="{{ $subtotal }}" readonly
                                                                style="text-align: right;" />
                                                        </td>
                                                    </tr>

                                                    <!-- Discount -->
                                                    <tr>
                                                        <td><label for="total_discount">Discount</label></td>
                                                        <td class="text-end">
                                                            <input type="number" id="total_discount"
                                                                name="total_discount" class="form-control" step="0.01"
                                                                placeholder="Enter Discount"
                                                                value="{{ $purchase->discount }}"
                                                                style="text-align: right;" />
                                                        </td>
                                                    </tr>

                                                    <!-- Net Amount -->
                                                    <tr>
                                                        <td><label for="total_netamount">Net Amount</label></td>
                                                        <td>
                                                            <input type="number" id="total_netamount"
                                                                name="total_netamount"
                                                                value="{{ $purchase->total_netamount }}"
                                                                style="text-align: right;" class="form-control"
                                                                readonly />
                                                        </td>
                                                    </tr>

                                                    <!-- TAX -->
                                                    <tr>
                                                        <td>
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox" name="include_tax" id="include_tax" {{ old('include_tax', $purchase->include_tax ?? 0) ? 'checked' : '' }}>
                                                                <label for="include_tax" class="me-3">
                                                                    Include TAX (%)
                                                                    <input type="number" name="tax" id="tax"
                                                                        value="{{ $purchase->tax }}" min="0"
                                                                        class="form-control form-control-sm d-inline-block text-end"
                                                                        step="0.01" placeholder="TAX"
                                                                        style="width: 70px; margin-left: 10px;" />
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <input type="text" id="tax_amount" name="tax_amount"
                                                                class="form-control text-end" readonly
                                                                placeholder="TAX Amount"
                                                                value="{{ $purchase->tax_amount }}" readonly
                                                                style="text-align: right;" />
                                                        </td>
                                                    </tr>

                                                    <!-- VAT -->
                                                    <tr>
                                                        <td>
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox" name="include_vat" id="include_vat" {{ old('include_vat', $purchase->include_vat ?? 0) ? 'checked' : '' }}>
                                                                <label for="include_vat">
                                                                    Include VAT (%)
                                                                    <input type="number" id="vat" name="vat"
                                                                        value="{{ $purchase->vat }}" min="0"
                                                                        class="form-control form-control-sm vat-input text-end"
                                                                        step="0.01" placeholder="VAT"
                                                                        style="width: 70px; display: inline-block; margin-left: 10px;" />
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <input type="text" id="vat_amount" name="vat_amount"
                                                                value="{{ $purchase->vat_amount }}"
                                                                class="form-control text-end" readonly
                                                                placeholder="VAT Amount" style="text-align: right;" />
                                                        </td>
                                                    </tr>

                                                    <!-- Grand Total -->
                                                    <tr>
                                                        <td><label for="grand_total">Grand Total</label></td>
                                                        <td class="text-end">
                                                            <input type="text" id="grand_total" name="grand_total"
                                                                class="form-control text-end"
                                                                value="{{ $purchase->grand_total }}" readonly
                                                                style="text-align: right;" />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <hr>

                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Remarks</label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the remarks"></textarea>
                                </div>
                                <div class="row text-right">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>
                                            Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('backend.admin.supplier.supplier_modal')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('#supplier').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const supplierId = selectedOption.val();
                const supplierName = selectedOption.data('name') || 'N/A';
                const supplierCompany = selectedOption.data('company') || 'N/A';
                const supplierPhone = selectedOption.data('phone') || 'N/A';
                const supplierEmail = selectedOption.data('email') || 'N/A';

                if (supplierId) {
                    $('#supplier-details-table').show();
                    $('#supplier-details-body').empty();

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
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route('admin.supplier2.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {

                    if (response.success) {

                        $('#createSupplierModal').modal('hide');

                        $('#createSupplierForm')[0].reset();

                        $('#supplier').append(new Option(response.supplier.name, response.supplier.id));

                        $('#supplier').trigger('change');

                        toastr.success('Supplier added successfully!');
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function(response) {

                    let errors = response.responseJSON.errors;
                    for (let field in errors) {
                        $(`#new_supplier_${field}`).addClass('is-invalid');
                        $(`#new_supplier_${field}`).after(
                            `<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

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

                        if (Array.isArray(response.products) && response.products.length > 0) {

                            response.products.forEach(function(product) {
                                let unitName = product.unit && product.unit.name ? product.unit
                                    .name : '';

                                $productSelect.append(`
                                <option value="${product.id}" 
                                        data-id="${product.id}" 
                                        data-speciphiction="${product.description}"
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
                        $productSelect.empty().append(
                            '<option value="">Error fetching products</option>');
                    }
                });
            }

            $(document).on('change', '.category-select', function() {
                var categoryId = $(this).val();
                var row = $(this).closest('tr');
                loadProductsByCategory(categoryId, row);
            });

            // Add new row (HTML string like in create page)
            $(document).on('click', '.add-row', function() {
                let newRow = `
                <tr>
                    <td>
                        <select name="category_id" class="form-control category-select select2" style="width: 100%;">
                            <option value="all">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width:17% !important;">
                        <select name="aproducts[]" class="form-control select2 product-select" style="width: 100%;">
                            <option value="">Select Product</option>
                            @foreach ($aproducts as $aproduct)
                                <option value="{{ $aproduct->id }}"
                                    data-price="{{ $aproduct->price }}"
                                    data-unit="{{ $aproduct->unit->name }}"
                                    data-speciphiction="{{ $aproduct->description }}">
                                    {{ $aproduct->name }}{{ $aproduct->product_code ? ' (' . $aproduct->product_code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="width:14% !important;">
                        <input type="text" name="speciphictions[]" class="form-control speciphictions" readonly>
                    </td>
                    <td><input type="text" name="order_unit[]" class="form-control unit-input" readonly></td>
                    <td><input type="number" name="unit_price[]" class="form-control unit-price" step="0.01"  style="text-align: right;"></td>
                    <td><input type="number" name="quantity[]" class="form-control quantity" value="{{ 1 }}"></td>
                    <td><input type="text" name="total[]" class="form-control subtotal" readonly style="text-align: right;"></td>
                   <td class="col-1">
                        <button type="button" class="btn btn-success btn-sm me-1 add-row">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-minus"></i>
                        </button>
                    </td>
                </tr>`;

                $('#product-tbody').append(newRow);
                $('.select2').select2(); // Re-initialize select2 for new elements
            });


            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateSubtotal();
            });

            // On product select, set unit price and unit
            $(document).on('change', '.product-select', function() {
                const selected = $(this).find(':selected');
                const row = $(this).closest('tr');
                // row.find('.unit-price').val(selected.data('price'));
                // row.find('.unit-input').val(selected.data('unit'));
                let productPrice = selected.data('price') || 0;
                let productUnit = selected.data('unit') || '';
                let productQuantity = selected.data('quantity') || 1;
                let productId = selected.val();
                let productSpeciphiction = selected.data('speciphiction') || "N";
                console.log(productSpeciphiction);
                //console.log(productQuantity);

                let isDuplicate = false;
                $('.product-select').not(this).each(function() {
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
                    row.find('.speciphictions').val(productSpeciphiction);
                    row.find('.unit-price').val(productPrice);
                    row.find('.quantity').val(productQuantity);
                    row.find('.subtotal').val(productPrice);
                    row.find('.total').val(productPrice);
                    row.find('.unit-input').val(productUnit);
                    row.find('.product-discount').val(0);

                    //console.log(productPrice);

                    addToHiddenFields(productId, productQuantity, productPrice, 0);

                } else {
                    //console.log("productId not found1");
                }

                calculateRowTotal(row);
            });

            // Quantity change
            $(document).on('input', '.quantity', function() {
                const row = $(this).closest('tr');
                calculateRowTotal(row);
            });

            // Calculate total for each row
            function calculateRowTotal(row) {
                const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
                const quantity = parseFloat(row.find('.quantity').val()) || 1;
                const discount = parseFloat(row.find('.product-discount').val()) || 0;

                const subtotal = unitPrice * quantity;
                const total = subtotal - discount;

                row.find('.subtotal').val(subtotal.toFixed(2));
                row.find('.total').val(total.toFixed(2));
            }

            // Update subtotal and grand total
            function updateSubtotal() {
                let subtotal = 0;
                $('.subtotal').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });

                $('#subtotal').val(subtotal.toFixed(2));

                const discount = parseFloat($('#total_discount').val()) || 0;
                const netAmount = subtotal - discount;
                $('#total_netamount').val(netAmount.toFixed(2));

                let taxAmount = 0;
                let vatAmount = 0;

                if ($('#include_tax').is(':checked')) {
                    const taxRate = parseFloat($('#tax').val()) || 0;
                    taxAmount = (netAmount * taxRate / 100);
                    $('#tax_amount').val(taxAmount.toFixed(2));
                } else {
                    $('#tax_amount').val('');
                }

                if ($('#include_vat').is(':checked')) {
                    const vatRate = parseFloat($('#vat').val()) || 0;
                    vatAmount = (netAmount * vatRate / 100);
                    $('#vat_amount').val(vatAmount.toFixed(2));
                } else {
                    $('#vat_amount').val('');
                }

                const grandTotal = netAmount + taxAmount + vatAmount;
                $('#grand_total').val(grandTotal.toFixed(2));
            }

            // Discount / Tax / VAT change
            $('#total_discount, #tax, #vat').on('input', function() {
                updateSubtotal();
            });

            // Enable tax input
            $('#include_tax').change(function() {
                $('#tax').prop('disabled', !this.checked);
                updateSubtotal();
            });

            // Enable vat input
            $('#include_vat').change(function() {
                $('#vat').prop('disabled', !this.checked);
                updateSubtotal();
            });

            // Trigger initial calculations
            $('#product-tbody tr').each(function() {
                calculateRowTotal($(this));
            });
        });
    </script>
@endpush
