@extends('layouts.admin', ['pageTitle' => 'Create Purchase Invoice'])
@section('admin')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'Create Purchase Invoice' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'Create Purchase Invoice' }}</li>
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
                                <h4 class="mb-0">Create New Purchase Invoice</h4>
                                <a href="{{ route('admin.purchase.invoice.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.purchase.invoice.store') }}" enctype="multipart/form-data" id="purchaseInvoiceForm">
                                @csrf

                                <input type="hidden" name="product_ids" id="product_ids">
                                <input type="hidden" name="quantities" id="quantities">
                                <input type="hidden" name="prices" id="prices">
                                <input type="hidden" name="discounts" id="discounts">
                                <input type="hidden" name="grand_total" id="grand_total_hidden">
                                <input type="hidden" name="purchase_id" id="purchase_id_hidden" value="">

                                <div class="row">
                                    <!-- Supplier Select -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="supplier">Vendor <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="supplier" id="supplier"
                                                class="form-control select2 @error('supplier') is-invalid @enderror"
                                                required>
                                                <option value="" disabled selected>Select Vendor</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" 
                                                        data-name="{{ $supplier->name }}"
                                                        data-company="{{ $supplier->company }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-email="{{ $supplier->email }}"
                                                        {{ old('supplier') == $supplier->id ? 'selected' : '' }}>
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
                                                        {{ old('projects') == $project->id ? 'selected' : '' }}>
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

                                    <!-- Select Invoice NO -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="purchase_id">PO Number</label>
                                        <div class="input-group">
                                            <select name="purchase_id" id="purchase_id"
                                                class="form-control select2 @error('purchase_id') is-invalid @enderror">
                                                <option value="" selected>Select PO Number No</option>
                                                @foreach ($purchases as $purchase)
                                                    <option value="{{ $purchase->id }}"
                                                        {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                                                        {{ $purchase->invoice_no }} ({{ $purchase->supplier->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('purchase_id')
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
                                            <table class="table table-bordered" id="supplier-details-table"
                                                style="display: none;">
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
                                            <div id="no-supplier-selected" class="text-muted">
                                                No vendor selected. Please select a vendor from the dropdown above.
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
                                                            <tr id="no-products-row">
                                                                <td colspan="8" class="text-center py-4">No items added yet. Select an invoice to copy items or add manually.</td>
                                                            </tr>
                                                            <!-- Dynamic rows will be inserted here -->
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
                                                placeholder="Enter any additional notes or description">{{ old('description') }}</textarea>
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
                                                                class="form-control text-right" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Discount -->
                                                    <tr>
                                                        <th class="text-right">Discount:</th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="total_discount" name="total_discount"
                                                                    class="form-control text-right" value="{{ old('total_discount', 0) }}"
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
                                                                class="form-control text-right" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Tax -->
                                                    <tr>
                                                        <th class="text-right">
                                                            <div class="icheck-success d-inline">
                                                                <input class="form-check-input" type="checkbox" name="include_tax"
                                                                    id="include_tax" value="1" {{ old('include_tax') ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="include_tax">Tax (%):</label>
                                                            </div>
                                                        </th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="tax" name="tax"
                                                                    class="form-control text-right" value="{{ old('tax', $tax ?? 0) }}"
                                                                    min="0" step="0.01" placeholder="0.00" disabled />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Tax Amount -->
                                                    <tr id="tax_amount_row" style="display: none;">
                                                        <th class="text-right">Tax Amount:</th>
                                                        <td class="text-right">
                                                            <input type="text" id="tax_amount" name="tax_amount"
                                                                class="form-control text-right" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- VAT -->
                                                    <tr>
                                                        <th class="text-right">
                                                              <div class="icheck-success d-inline">
                                                                <input class="form-check-input" type="checkbox" name="include_vat"
                                                                    id="include_vat" value="1" {{ old('include_vat') ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="include_vat">VAT (%):</label>
                                                            </div>
                                                        </th>
                                                        <td class="text-right">
                                                            <div class="input-group">
                                                                <input type="number" id="vat" name="vat"
                                                                    class="form-control text-right" value="{{ old('vat', $vat ?? 0) }}"
                                                                    min="0" step="0.01" placeholder="0.00" disabled />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- VAT Amount -->
                                                    <tr id="vat_amount_row" style="display: none;">
                                                        <th class="text-right">VAT Amount:</th>
                                                        <td class="text-right">
                                                            <input type="text" id="vat_amount" name="vat_amount"
                                                                class="form-control text-right" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                    <!-- Grand Total -->
                                                    <tr class="table-active">
                                                        <th class="text-right"><h5>Grand Total:</h5></th>
                                                        <td class="text-right">
                                                            <input type="text" id="grand_total" name="grand_total"
                                                                class="form-control text-right font-weight-bold" value="0" readonly />
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 text-right">
                                        <button type="reset" class="btn btn-secondary mr-2">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Create Invoice
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

    <!-- Modal for creating a new supplier -->
    @include('backend.admin.supplier.supplier_modal')
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
                    $('#no-supplier-selected').hide();
                    $('#supplier-details-body').html(`  
                        <tr>
                            <td>${supplierName}</td>
                            <td>${supplierCompany}</td>
                            <td>${supplierPhone}</td>
                            <td>${supplierEmail}</td>
                        </tr>
                    `);
                } else {
                    $('#supplier-details-table').hide();
                    $('#no-supplier-selected').show();
                }
            });

            // Project change event to load invoices
            $('#project').on('change', function() {
                var projectId = $(this).val();
    
                // 1. Reset purchase dropdown
                $('#purchase_id').empty().append('<option value="">Select Invoice No</option>');
                
                // 2. Reset product table
                $('#product-table tbody').html(`
                    <tr id="no-products-row">
                        <td colspan="8" class="text-center py-4">No items added yet. Select an invoice to copy items.</td>
                    </tr>
                `);
                
                // 3. Reset all calculation fields
                $('#subtotal').val('0');
                $('#total_discount').val('0');
                $('#total_netamount').val('0');
                $('#tax_amount').val('0');
                $('#vat_amount').val('0');
                $('#grand_total').val('0');
                
                // 4. Reset checkboxes
                $('#include_tax').prop('checked', false);
                $('#include_vat').prop('checked', false);
                $('#tax').prop('disabled', true);
                $('#vat').prop('disabled', true);
                
                // 5. Hide tax/vat amount rows
                $('#tax_amount_row').hide();
                $('#vat_amount_row').hide();

                
                if (projectId) {
                    $.ajax({
                        url: '/admin/purchase/get-purchases-by-project/' + projectId, 
                        type: 'GET',
                        data: { project_id: projectId },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#purchase_id').append('<option value="' + value.id + '">' + value.invoice_no + '</option>');
                            });
                        },
                        error: function() {
                            toastr.error('Failed to load invoices for this project');
                        }
                    });
                }
            });

            // Invoice selection event to load products
            $('#purchase_id').on('change', function() {
                var purchaseId = $(this).val();  // Correct variable name
                $('#purchase_id_hidden').val(purchaseId); 
                if (purchaseId) {
                    $.ajax({
                        url: '/admin/purchase/get-products-by-purchase/' + purchaseId,  // Fixed variable name
                        type: 'GET',
                        success: function(data) {
                            let html = '';
                            let subtotal = 0;

                            if (data.length > 0) {
                                data.forEach(function(item) {
                                    const product = item.product || {};
                                    const category = product.category || {};
                                    const unit = product.unit || {};

                                    const categoryName = category.name || 'N/A';
                                    const itemName = product.name || 'N/A';
                                    const specifications = product.description || 'N/A';
                                    const orderUnit = unit.name || 'pcs'; 
                                    const qty = parseFloat(item.quantity) || 0;
                                    const price = parseFloat(item.price) || 0;
                                    const rowTotal = (qty * price).toFixed(2);

                                    subtotal += parseFloat(rowTotal);

                                    html += `
                                        <tr class="product-row" data-product-id="${product.id}">
                                            <td><strong>${categoryName}</strong></td>
                                            <td>${itemName}</td>
                                            <td>${specifications}</td>
                                            <td>${orderUnit}</td>
                                            <td><input type="number" class="form-control qty" value="${qty}" min="0" step="1" /></td>
                                            <td><input type="number" class="form-control unit_price" value="${price}" min="0" step="0.01" /></td>
                                            <td><input type="text" class="form-control row_total" value="${rowTotal}" readonly /></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                                        </tr>
                                    `;
                                });
                            } else {
                                html = `<tr id="no-products-row"><td colspan="8" class="text-center py-4">No items found in this invoice</td></tr>`;
                            }

                            $('#product-table tbody').html(html);
                            $('#subtotal').val(subtotal.toFixed(2));
                            calculateTotal();
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            toastr.error('Failed to load products from this invoice');
                        }
                    });
                } else {
                    $('#product-table tbody').html('<tr id="no-products-row"><td colspan="8" class="text-center py-4">No items added yet</td></tr>');
                    $('#subtotal').val('0');
                    calculateTotal();
                }
            });

            // Remove row button
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateSubtotal();
                calculateTotal();
                
                // If no rows left, show the no products message
                if ($('.product-row').length === 0) {
                    $('#product-table tbody').html('<tr id="no-products-row"><td colspan="8" class="text-center py-4">No items added yet</td></tr>');
                }
            });

            // Quantity and price change events
            $(document).on('input', '.qty, .unit_price', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty').val()) || 0;
                const price = parseFloat(row.find('.unit_price').val()) || 0;
                const total = (qty * price).toFixed(2);

                row.find('.row_total').val(total);
                updateSubtotal();
                calculateTotal();
            });

            // Discount, tax, vat change events
            $('#total_discount, #tax, #vat').on('input', function() {
                calculateTotal();
            });

            // Include tax checkbox
            $('#include_tax').on('change', function() {
                $('#tax').prop('disabled', !$(this).is(':checked'));
                $('#tax_amount_row').toggle($(this).is(':checked'));
                calculateTotal();
            });

            // Include vat checkbox
            $('#include_vat').on('change', function() {
                $('#vat').prop('disabled', !$(this).is(':checked'));
                $('#vat_amount_row').toggle($(this).is(':checked'));
                calculateTotal();
            });

            // Form submission handler
            $('#purchaseInvoiceForm').on('submit', function(e) {
                // Collect all product data
                let productIds = [];
                let quantities = [];
                let prices = [];
                let discounts = [];
                
                $('.product-row').each(function() {
                    productIds.push($(this).data('product-id') || '');
                    quantities.push($(this).find('.qty').val() || 0);
                    prices.push($(this).find('.unit_price').val() || 0);
                    discounts.push(0); // Assuming no individual discounts
                });
                
                // Set the hidden input values
                $('#product_ids').val(JSON.stringify(productIds));
                $('#quantities').val(JSON.stringify(quantities));
                $('#prices').val(JSON.stringify(prices));
                $('#discounts').val(JSON.stringify(discounts));
                $('#grand_total_hidden').val($('#grand_total').val());
                
                if (!$('#purchase_id_hidden').val()) {
                    $('#purchase_id_hidden').val($('#purchase_id').val());
                }

                // Validate at least one product is added
                if (productIds.length === 0) {
                    toastr.error('Please add at least one product to the invoice');
                    e.preventDefault();
                    return false;
                }
                
                return true;
            });

            // Helper function to update subtotal
            function updateSubtotal() {
                let subtotal = 0;
                $('.row_total').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                $('#subtotal').val(subtotal.toFixed(2));
            }

            // Helper function to calculate totals
            function calculateTotal() {
                const subtotal = parseFloat($('#subtotal').val()) || 0;
                const discount = parseFloat($('#total_discount').val()) || 0;
                const taxRate = $('#include_tax').is(':checked') ? parseFloat($('#tax').val()) || 0 : 0;
                const vatRate = $('#include_vat').is(':checked') ? parseFloat($('#vat').val()) || 0 : 0;
                const total = subtotal - discount;
                const netAmount = subtotal - discount;
                const taxAmount = (netAmount * taxRate) / 100;
                const vatAmount = (netAmount * vatRate) / 100;
                const grandTotal = netAmount + taxAmount + vatAmount;

                $('#total_netamount').val(netAmount.toFixed(2));
                $('#tax_amount').val(taxAmount.toFixed(2));
                $('#vat_amount').val(vatAmount.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));
            }

            // Initialize tax/vat checkboxes
            if ($('#include_tax').is(':checked')) {
                $('#tax').prop('disabled', false);
                $('#tax_amount_row').show();
            }
            
            if ($('#include_vat').is(':checked')) {
                $('#vat').prop('disabled', false);
                $('#vat_amount_row').show();
            }
        });
    </script>
@endpush