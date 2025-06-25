@extends('layouts.admin', ['pageTitle' => 'Purchase'])
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
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('admin.purchase.index') }}" class="btn btn-sm btn-danger rounded-0">
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
                                        <label for="supplier">Vendor</label>
                                        <div class="input-group">
                                            <select name="supplier" id="supplier"
                                                class="form-control select2 @error('supplier') is-invalid @enderror"
                                                required>
                                                {{-- <option value="" disabled>Select Vendor</option> --}}
                                                <option value="" disabled {{ old('supplier') ? '' : 'selected' }}>
                                                    Select Vendor</option>
                                                @foreach ($suppliers as $supplier)
                                                    <!-- <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option> -->

                                                    <option value="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                                        data-company="{{ $supplier->company }}"
                                                        data-phone="{{ $supplier->phone }}"
                                                        data-email="{{ $supplier->email }}"
                                                        {{ old('supplier') == $supplier->id ? 'selected' : '' }}>
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

                                    <!-- Project Select with Search Feature -->
                                    <!-- Project Select with Search Feature -->
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label for="project">Project</label>
                                        <div class="input-group">
                                            <select name="projects" id="project"
                                                class="form-control select2 @error('project') is-invalid @enderror"
                                                style="width: 100%;">
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        data-items='@json($project->items)'>
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
                                        <label for="purchase_id">Invoice No</label>
                                        <div class="input-group">
                                            <select name="purchase_id" id="purchase_id"
                                                class="form-control select2 @error('purchase_id') is-invalid @enderror">
                                                <option value="">Select Invoice No</option>
                                                @foreach ($purchases as $purchase)
                                                    <option value="{{ $purchase->id }}" data-name="{{ $purchase->name }}"
                                                        data-company="{{ $purchase->company }}"
                                                        data-phone="{{ $purchase->phone }}"
                                                        data-email="{{ $purchase->email }}"
                                                        {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                                                        {{ $purchase->invoice_no }}
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

                                <!-- Product Table -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="product-table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Item</th>
                                                        <th>Speciphications</th>
                                                        <th>Order Unit</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Total</th>
                                                        <th>Action</th>
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
                                    <!-- First Row: Subtotal and Total Discount -->
                                    <div class="row w-100">
                                        <div class="col-12 col-lg-6 mb-2">
                                        </div>
                                        <div class="col-12 col-lg-6 mb-2">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <!-- Subtotal and Discount Row -->
                                                    <tr>
                                                        <td><label for="subtotal">Total Amount</label></td>
                                                        <td>
                                                            <div class="col-12 col-lg-12">
                                                                <input type="text" id="subtotal" name="subtotal"
                                                                    class="form-control" value="0" readonly
                                                                    style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label for="total_discount">Discount</label></td>
                                                        <td>
                                                            <div class="col-12 col-lg-12">
                                                                <input type="number" id="total_discount"
                                                                    name="total_discount" class="form-control"
                                                                    step="0.01" placeholder="Enter Discount"
                                                                    style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label for="total_netamount">Net Amount</label></td>
                                                        <td>
                                                            <div class="col-12 col-lg-12">
                                                                <input type="number" id="total_netamount"
                                                                    name="total_netamount" class="form-control"
                                                                    step="0.01" readonly style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Include VAT and TAX Checkboxes -->
                                                    <tr>
                                                        <td>
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox" name="include_tax"
                                                                    id="include_tax">
                                                                <!-- Include TAX -->
                                                                <label for="include_tax" class="me-3">
                                                                    Include TAX (%)
                                                                    <input type="number" name="tax" id="tax"
                                                                        value="{{ $tax }}" min="0"
                                                                        class="form-control form-control-sm d-inline-block"
                                                                        step="0.01" placeholder="Enter TAX"
                                                                        style="width: 70px; margin-left: 10px;" disabled />
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="col-12 col-lg-12 tax-fields">
                                                                <input type="text" id="tax_amount" name="tax_amount"
                                                                    class="form-control" readonly placeholder="TAX Amount"
                                                                    style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="icheck-success d-inline">
                                                                <input type="checkbox" name="include_vat"
                                                                    id="include_vat">
                                                                <label for="include_vat">
                                                                    Include VAT (%)
                                                                    <input type="number" id="vat" name="vat"
                                                                        value="{{ $vat }}" min="0"
                                                                        class="form-control form-control-sm vat-input"
                                                                        step="0.01" placeholder="Enter VAT"
                                                                        style="width: 70px; display: inline-block; margin-left: 10px;"
                                                                        disabled />
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="col-12 col-lg-12 vat-fields">
                                                                <input type="text" id="vat_amount" name="vat_amount"
                                                                    class="form-control" readonly placeholder="VAT Amount"
                                                                    style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Grand Total Row -->
                                                    <tr>
                                                        <td><label for="grand_total">Grand Total</label></td>
                                                        <td>
                                                            <div class="col-12 col-lg-12">
                                                                <input type="text" id="grand_total" name="grand_total"
                                                                    class="form-control" value="0" readonly
                                                                    style="text-align: right;" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Description -->
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"
                                        placeholder="Enter the description"></textarea>
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

    <!-- Modal for creating a new supplier -->
    @include('backend.admin.supplier.supplier_modal')
@endsection

@push('js')
    {{-- Supplier selection event --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2();

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
                    <tr id="supplier-row">
                        <td>${supplierName}</td>
                        <td>${supplierCompany}</td>
                        <td>${supplierPhone}</td>
                        <td>${supplierEmail}</td>
                    </tr>
                `);
                } else {
                    $('#supplier-details-table').hide();
                }
            });

            // Supplier creation event
            $('#createSupplierForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                let formData = $(this).serialize(); // Get form data

                $.ajax({
                    url: '{{ route('admin.supplier2.store') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Close the modal
                            $('#createSupplierModal').modal('hide');

                            // Clear form inputs
                            $('#createSupplierForm')[0].reset();

                            // Create a new option with data attributes
                            let newOption = $('<option>', {
                                value: response.supplier.id,
                                text: response.supplier.name,
                                'data-name': response.supplier.name,
                                'data-company': response.supplier.company,
                                'data-phone': response.supplier.phone,
                                'data-email': response.supplier.email
                            });

                            // Insert new supplier AFTER "Select Vendor" option
                            $('#supplier option:first').after(newOption);

                            // Select the newly added supplier
                            $('#supplier').val(response.supplier.id).trigger('change');

                            // Show success message
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
        });
    </script>

    <script>
        $(document).ready(function() {
            // Project change করলে invoice load এবং আগের সব ডেটা ক্লিয়ার
            $('#project').on('change', function () {
                var projectId = $(this).val();

                // ✅ Invoice ড্রপডাউন clear করো
                $('#purchase_id').empty().append('<option value="">Select Invoice No</option>');

                // ✅ Product table clear করো
                $('#product-table tbody').html(`
                    <tr id="no-products-row">
                        <td colspan="8" class="text-center">No product found</td>
                    </tr>
                `);

                // ✅ Subtotal, discount, VAT, TAX, Grand Total সব clear করো
                $('#subtotal').val('0');
                $('#total_discount').val('');
                $('#total_netamount').val('');
                $('#tax').val('{{ $tax }}'); // default tax যদি থাকে
                $('#vat').val('{{ $vat }}'); // default vat যদি থাকে
                $('#tax_amount').val('');
                $('#vat_amount').val('');
                $('#grand_total').val('');

                // ✅ Checkbox disable & unchecked করো
                $('#include_tax').prop('checked', false);
                $('#include_vat').prop('checked', false);
                $('#tax').prop('disabled', true);
                $('#vat').prop('disabled', true);

                // ✅ যদি নতুন প্রজেক্ট সিলেক্ট করা হয়, ইনভয়েস লোড করো
                if (projectId) {
                    $.ajax({
                        url: '/admin/purchase/get-purchases-by-project/' + projectId,
                        type: 'GET',
                        success: function (data) {
                            $.each(data, function (key, value) {
                                $('#purchase_id').append('<option value="' + value.id + '">' + value.invoice_no + '</option>');
                            });
                        }
                    });
                }
            });

            // Invoice No change করলে Products Load
            $('#purchase_id').on('change', function() {
                var purchaseId = $(this).val();

                if (purchaseId) {
                    $.ajax({
                        url: '/admin/purchase/get-products-by-purchase/' + purchaseId,
                        type: 'GET',
                        success: function(items) {
                            // এখানে তুমি item গুলা দেখাবে তোমার HTML table বা list-এ
                            console.log('Products:', items);

                            let html = '';
                            items.forEach(function(item, index) {
                                html += `<li>${item.name} (${item.quantity})</li>`;
                            });

                            $('#product-list').html(html);
                        }
                    });
                }
            });

            // Invoice select করলে Product Table load করো
            $('#purchase_id').on('change', function() {
                var purchaseId = $(this).val();

                if (purchaseId) {
                    $.ajax({
                        url: '/admin/purchase/get-products-by-purchase/' + purchaseId,
                        type: 'GET',
                        success: function(data) {
                            let html = '';
                            let subtotal = 0;

                            if (data.length > 0) {
                                data.forEach(function(item, index) {
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
                                        <tr class="product-row">
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
                                html =
                                    `<tr><td colspan="8" class="text-center">No products found</td></tr>`;
                            }

                            $('#product-table tbody').html(html);
                            $('#subtotal').val(subtotal.toFixed(2));
                            calculateTotal(); // Update net/grand total
                        },

                        error: function() {
                            alert('Failed to load products.');
                        }
                    });
                } else {
                    $('#product-table tbody').html(`
                <tr><td colspan="8" class="text-center">No product found</td></tr>
            `);
                }
            });

            // Optional: Remove button for dynamically loaded rows
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // Row-based subtotal calculation
            $(document).on('input', '.qty, .unit_price', function() {
                const row = $(this).closest('tr');
                const qty = parseFloat(row.find('.qty').val()) || 0;
                const price = parseFloat(row.find('.unit_price').val()) || 0;
                const total = (qty * price).toFixed(2);

                row.find('.row_total').val(total);
                updateSubtotal();
                calculateTotal();
            });

            // Remove Row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                updateSubtotal();
                calculateTotal();
            });

            // Update Subtotal
            function updateSubtotal() {
                let subtotal = 0;
                $('.row_total').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                $('#subtotal').val(subtotal.toFixed(2));
            }

            // Calculate Net Amount and Grand Total
            function calculateTotal() {
                const subtotal = parseFloat($('#subtotal').val()) || 0;
                const discount = parseFloat($('#total_discount').val()) || 0;
                const taxRate = $('#include_tax').is(':checked') ? parseFloat($('#tax').val()) || 0 : 0;
                const vatRate = $('#include_vat').is(':checked') ? parseFloat($('#vat').val()) || 0 : 0;

                const netAmount = subtotal - discount;
                const taxAmount = (netAmount * taxRate) / 100;
                const vatAmount = (netAmount * vatRate) / 100;
                const grandTotal = netAmount + taxAmount + vatAmount;

                $('#total_netamount').val(netAmount.toFixed(2));
                $('#tax_amount').val(taxAmount.toFixed(2));
                $('#vat_amount').val(vatAmount.toFixed(2));
                $('#grand_total').val(grandTotal.toFixed(2));
            }

            $('#total_discount, #tax, #vat').on('input', function() {
                calculateTotal();
            });

            $('#include_tax, #include_vat').on('change', function() {
                // Enable/disable input field based on checkbox
                $('#tax').prop('disabled', !$('#include_tax').is(':checked'));
                $('#vat').prop('disabled', !$('#include_vat').is(':checked'));
                calculateTotal();
            });

        });
    </script>
@endpush
