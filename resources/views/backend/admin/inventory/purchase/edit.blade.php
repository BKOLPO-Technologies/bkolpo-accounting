@extends('layouts.admin', ['pageTitle' => 'Edit Purchase Invoice'])
@section('admin')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Purchase Invoice: {{ $invoice->invoice_no }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.purchase.index') }}">Purchase Invoices</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                                <h4 class="mb-0">Edit Purchase Invoice</h4>
                                <a href="{{ route('admin.purchase.invoice.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.purchase.invoice.update', $invoice->id) }}" enctype="multipart/form-data" id="purchaseInvoiceForm">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="product_ids" id="product_ids" value="{{ json_encode($invoice->items->pluck('product_id')) }}">
                                <input type="hidden" name="quantities" id="quantities">
                                <input type="hidden" name="prices" id="prices">
                                <input type="hidden" name="discounts" id="discounts">
                                <input type="hidden" name="grand_total" id="grand_total_hidden" value="{{ $invoice->grand_total }}">
                                <input type="hidden" name="purchase_id" id="purchase_id_hidden" value="{{ $invoice->purchase_id }}">

                                <div class="row">
                                    <!-- Supplier Select -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="supplier">Vendor <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="supplier" id="supplier"
                                                class="form-control select2 @error('supplier') is-invalid @enderror"
                                                required>
                                                <option value="" disabled>Select Vendor</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" 
                                                        data-name="{{ $supplier->name }}"
                                                        data-company="{{ $supplier->company }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-email="{{ $supplier->email }}"
                                                        {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }} ({{ $supplier->company }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('supplier')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Project Select -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="project">Project</label>
                                        <div class="input-group">
                                            <select name="projects" id="project"
                                                class="form-control select2 @error('project') is-invalid @enderror"
                                                style="width: 100%;">
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        {{ $invoice->project_id == $project->id ? 'selected' : '' }}>
                                                        {{ $project->project_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('project')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Invoice Date -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                                        <input type="date" name="invoice_date" id="invoice_date"
                                            class="form-control @error('invoice_date') is-invalid @enderror"
                                           value="{{ old('invoice_date', \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d')) }}" required>
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
                                        <div class="callout callout-info">
                                            <h5><i class="fas fa-info-circle"></i> Vendor Details</h5>
                                            <div class="table-responsive">
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
                                                        <tr>
                                                            <td>{{ $invoice->supplier->name }}</td>
                                                            <td>{{ $invoice->supplier->company }}</td>
                                                            <td>{{ $invoice->supplier->phone }}</td>
                                                            <td>{{ $invoice->supplier->email }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Table -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Purchase Items</h3>
                                                <button type="button" class="btn btn-primary btn-sm float-right" id="addProductBtn">
                                                    <i class="fas fa-plus"></i> Add Item
                                                </button>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table id="product-table" class="table table-bordered table-hover">
                                                        <thead class="bg-primary">
                                                            <tr>
                                                                <th width="15%">Category</th>
                                                                <th width="20%">Item</th>
                                                                <th width="20%">Specifications</th>
                                                                <th width="10%">Unit</th>
                                                                <th width="10%">Quantity</th>
                                                                <th width="10%">Price</th>
                                                                <th width="10%">Total</th>
                                                                <th width="5%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($invoice->items as $item)
                                                            <tr class="product-row" data-product-id="{{ $item->product_id }}">
                                                                <td><strong>{{ $item->product->category->name ?? 'N/A' }}</strong></td>
                                                                <td>{{ $item->product->name }}</td>
                                                                <td>{{ $item->product->description ?? 'N/A' }}</td>
                                                                <td>{{ $item->product->unit->name ?? 'pcs' }}</td>
                                                                <td><input type="number" class="form-control qty" value="{{ $item->quantity }}" min="0" step="1"></td>
                                                                <td><input type="number" class="form-control unit_price" value="{{ $item->price }}" min="0" step="0.01"></td>
                                                                <td><input type="text" class="form-control row_total" value="{{ number_format($item->quantity * $item->price, 2) }}" readonly></td>
                                                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary Section -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <!-- Description -->
                                        <div class="form-group">
                                            <label for="description">Notes</label>
                                            <textarea id="description" name="description" class="form-control" rows="3"
                                                placeholder="Enter any additional notes or description">{{ old('description', $invoice->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                    <tr>
                                                        <th class="text-right">Subtotal:</th>
                                                        <td class="text-right" width="30%">
                                                            <input type="text" id="subtotal" name="subtotal"
                                                                class="form-control text-right" value="{{ number_format($invoice->subtotal, 2) }}" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Discount -->
                                                    <tr>
                                                        <th class="text-right">Discount:</th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="total_discount" name="total_discount"
                                                                    class="form-control text-right" value="{{ $invoice->discount }}"
                                                                    min="0" step="0.01" placeholder="0.00" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">BDT</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Net Amount -->
                                                    <tr>
                                                        <th class="text-right">Net Amount:</th>
                                                        <td class="text-right">
                                                            <input type="text" id="total_netamount" name="total_netamount"
                                                                class="form-control text-right" value="{{ number_format($invoice->subtotal - $invoice->discount, 2) }}" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Tax -->
                                                    <tr>
                                                        <th class="text-right">
                                                            <div class="icheck-success d-inline">
                                                                <input class="form-check-input" type="checkbox" name="include_tax"
                                                                    id="include_tax" value="1" {{ $invoice->tax_rate > 0 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="include_tax">Tax (%):</label>
                                                            </div>
                                                        </th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="tax" name="tax"
                                                                    class="form-control text-right" value="{{ $invoice->tax_rate }}"
                                                                    min="0" step="0.01" placeholder="0.00" {{ $invoice->tax_rate > 0 ? '' : 'disabled' }} />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Tax Amount -->
                                                    <tr id="tax_amount_row" style="{{ $invoice->tax_rate > 0 ? '' : 'display: none;' }}">
                                                        <th class="text-right">Tax Amount:</th>
                                                        <td class="text-right">
                                                            <input type="text" id="tax_amount" name="tax_amount"
                                                                class="form-control text-right" value="{{ number_format($invoice->tax_amount, 2) }}" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- VAT -->
                                                    <tr>
                                                        <th class="text-right">
                                                            <div class="icheck-success d-inline">
                                                                <input class="form-check-input" type="checkbox" name="include_vat"
                                                                    id="include_vat" value="1" {{ $invoice->vat_rate > 0 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="include_vat">VAT (%):</label>
                                                            </div>
                                                        </th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="vat" name="vat"
                                                                    class="form-control text-right" value="{{ $invoice->vat_rate }}"
                                                                    min="0" step="0.01" placeholder="0.00" {{ $invoice->vat_rate > 0 ? '' : 'disabled' }} />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- VAT Amount -->
                                                    <tr id="vat_amount_row" style="{{ $invoice->vat_rate > 0 ? '' : 'display: none;' }}">
                                                        <th class="text-right">VAT Amount:</th>
                                                        <td class="text-right">
                                                            <input type="text" id="vat_amount" name="vat_amount"
                                                                class="form-control text-right" value="{{ number_format($invoice->vat_amount, 2) }}" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Grand Total -->
                                                    <tr class="table-active">
                                                        <th class="text-right"><h5>Grand Total:</h5></th>
                                                        <td class="text-right">
                                                            <input type="text" id="grand_total" name="grand_total"
                                                                class="form-control text-right font-weight-bold" value="{{ number_format($invoice->grand_total, 2) }}" readonly />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-right">

                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Update Invoice
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->unit->name ?? 'pcs' }}</td>
                                    <td>{{ number_format($product->cost_price, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary select-product"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-category="{{ $product->category->name ?? 'N/A' }}"
                                            data-description="{{ $product->description ?? 'N/A' }}"
                                            data-unit="{{ $product->unit->name ?? 'pcs' }}"
                                            data-price="{{ $product->cost_price }}">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(2.25rem + 2px) !important;
        }
        .table th {
            white-space: nowrap;
        }
        input[readonly] {
            background-color: #f8f9fa !important;
        }
        #itemsTableBody tr td {
            vertical-align: middle !important;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Supplier selection event
            $('#supplier').on('change', function() {
                const selectedOption = $(this).find(':selected');
                const supplierId = selectedOption.val();
                const supplierName = selectedOption.data('name') || '';
                const supplierCompany = selectedOption.data('company') || '';
                const supplierPhone = selectedOption.data('phone') || '';
                const supplierEmail = selectedOption.data('email') || '';

                if (supplierId) {
                    $('#supplier-details-table').show();
                    $('#supplier-details-body').html(`  
                        <tr>
                            <td>${supplierName}</td>
                            <td>${supplierCompany}</td>
                            <td>${supplierPhone}</td>
                            <td>${supplierEmail}</td>
                        </tr>
                    `);
                }
            });

            // Add product button click
            $('#addProductBtn').click(function() {
                $('#productModal').modal('show');
            });

            // Product selection from modal
            $(document).on('click', '.select-product', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const category = $(this).data('category');
                const description = $(this).data('description');
                const unit = $(this).data('unit');
                const price = $(this).data('price');

                // Check if product already exists in table
                if ($(`.product-row[data-product-id="${productId}"]`).length > 0) {
                    toastr.error('This product is already added to the invoice');
                    return;
                }

                // Add new row
                const newRow = `
                    <tr class="product-row" data-product-id="${productId}">
                        <td><strong>${category}</strong></td>
                        <td>${productName}</td>
                        <td>${description}</td>
                        <td>${unit}</td>
                        <td><input type="number" class="form-control qty" value="1" min="1" step="1"></td>
                        <td><input type="number" class="form-control unit_price" value="${price}" min="0" step="0.01"></td>
                        <td><input type="text" class="form-control row_total" value="${price}" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `;
                $('#product-table tbody').append(newRow);
                $('#productModal').modal('hide');
                updateProductIds();
                calculateTotals();
            });

            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateProductIds();
                calculateTotals();
                
                // If no rows left, show the no products message
                if ($('.product-row').length === 0) {
                    $('#product-table tbody').html('<tr id="no-products-row"><td colspan="8" class="text-center py-4">No items added yet</td></tr>');
                }
            });

            // Quantity/price change
            $(document).on('input', '.qty, .unit_price', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty').val()) || 0;
                const price = parseFloat(row.find('.unit_price').val()) || 0;
                const total = (qty * price).toFixed(2);
                row.find('.row_total').val(total);
                calculateTotals();
            });

            // Discount change
            $('#total_discount, #tax, #vat').on('input', calculateTotals);

            // Include tax checkbox
            $('#include_tax').on('change', function() {
                $('#tax').prop('disabled', !$(this).is(':checked'));
                $('#tax_amount_row').toggle($(this).is(':checked'));
                calculateTotals();
            });

            // Include vat checkbox
            $('#include_vat').on('change', function() {
                $('#vat').prop('disabled', !$(this).is(':checked'));
                $('#vat_amount_row').toggle($(this).is(':checked'));
                calculateTotals();
            });

            // Form submission handler
            $('#purchaseInvoiceForm').on('submit', function() {
                updateProductIds();
                return true;
            });

            // Calculate totals
            function calculateTotals() {
                // Calculate subtotal
                let subtotal = 0;
                $('.row_total').each(function() {
                    subtotal += parseFloat($(this).val().replace(/,/g, '')) || 0;
                });
                $('#subtotal').val(subtotal.toFixed(2));


                // Get discount and tax rates
                const discount = parseFloat($('#total_discount').val()) || 0;
                const taxRate = $('#include_tax').is(':checked') ? parseFloat($('#tax').val()) || 0 : 0;
                const vatRate = $('#include_vat').is(':checked') ? parseFloat($('#vat').val()) || 0 : 0;

                // Calculate net amount
                const netAmount = subtotal - discount;

                // Calculate taxes
                const taxAmount = (netAmount * taxRate) / 100;
                const vatAmount = (netAmount * vatRate) / 100;

                // Calculate grand total
                const grandTotal = netAmount + taxAmount + vatAmount;

                // Update fields
                $('#total_netamount').val(netAmount.toFixed(2));
                $('#tax_amount').val(taxAmount.toFixed(2));
                $('#vat_amount').val(vatAmount.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));
                $('#grand_total_hidden').val(grandTotal.toFixed(2));
            }

            // Update product IDs array
            function updateProductIds() {
                const productIds = [];
                const quantities = [];
                const prices = [];

                $('.product-row').each(function() {
                    productIds.push($(this).data('product-id'));
                    quantities.push($(this).find('.qty').val());
                    prices.push($(this).find('.unit_price').val());
                });

                $('#product_ids').val(JSON.stringify(productIds));
                $('#quantities').val(JSON.stringify(quantities));
                $('#prices').val(JSON.stringify(prices));
            }

            // Initialize calculations
            calculateTotals();
            updateProductIds();
        });
    </script>
@endpush