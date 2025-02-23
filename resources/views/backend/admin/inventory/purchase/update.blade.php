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
                            <div class="row">
                                <!-- Supplier Select -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="supplier">Supplier</label>
                                    <div class="input-group">
                                        <select name="supplier" id="supplier" class="form-control select2 @error('supplier') is-invalid @enderror">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" id="addSupplierBtn">
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
                                        <select name="product" id="product" class="form-control select2 @error('product') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->quantity }}">
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
                                <div class="col-lg-3 col-md-6 mb-3">
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
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="no-products-row">
                                                    <td colspan="6" class="text-center">No product found</td>
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

@endsection

@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script>
    // Initialize product table and searchable select
    let products = [];

    // Add product to the table
    $('#product').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const productName = selectedOption.data('name');
        const productPrice = parseFloat(selectedOption.data('price'));
        const productStock = parseInt(selectedOption.data('stock'));

        const productRow = `
            <tr>
                <td class="col-3">${productName}</td>
                <td class="col-2">${productPrice.toFixed(2)}</td>
                <td  class="col-1"><input type="number" class="quantity form-control" value="1" min="1" data-price="${productPrice}" data-stock="${productStock}" oninput="updateRow(this)" /></td>
                <td class="current-stock col-2">
                    <span class="badge bg-info">${productStock}</span>
                </td>
                <td class="subtotal">${productPrice.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;

        $('#product-table tbody').append(productRow);
        updateTotal();

        // Hide "No Product Found" row if there are products in the table
        $('#no-products-row').hide();

        // Reset product select
        $(this).val('');
    });


    // Remove product from the table
    $('#product-table').on('click', '.remove-product', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // Update row subtotal when quantity changes
    function updateRow(input) {
        const row = $(input).closest('tr');
        const price = parseFloat($(input).data('price'));
        const quantity = parseInt($(input).val());
        const stock = parseInt($(input).data('stock'));

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
        row.find('.subtotal').text(subtotal.toFixed(2));
        updateTotal();
    }

    // Calculate the subtotal, discount, and total
    function updateTotal() {
        let subtotal = 0;

        $('#product-table tbody tr').each(function() {
            const rowSubtotal = parseFloat($(this).find('.subtotal').text());
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
