@extends('layouts.admin', ['pageTitle' => 'Out Coming Chalan'])
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
                            <a href="{{ route('outcoming.chalan.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('outcoming.chalan.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mt-5">
                                <!-- Select Invoice NO -->
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="sale_id">Invoice No</label>
                                    <div class="input-group">
                                        <select name="sale_id" id="sale_id" class="form-control select2 @error('sale_id') is-invalid @enderror">
                                            <option value="">Select Invoice No</option>
                                            @foreach($sales as $sale)
                                                <option value="{{ $sale->id }}" 
                                                    data-name="{{ $sale->name }}" 
                                                    data-company="{{ $sale->company }}" 
                                                    data-phone="{{ $sale->phone }}" 
                                                    data-email="{{ $sale->email }}"
                                                    {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                                                    {{ $sale->invoice_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sale_id')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="invoice_date">Chalan Date</label>
                                    <input type="text" id="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" />
                                    @error('invoice_date')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Customer Details Table -->
                            <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="table-responsive-sm">
                                            <table class="table table-bordered" id="client-details-table" style="display: none;">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Supplier Name</th>
                                                        <th>Company</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="client-details-body"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                            <!-- Product Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive-sm">
                                        <table id="product-table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Receive Quantity</th>
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

                                <!-- Note -->
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Note</label>
                                    <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter Note"></textarea>
                                </div>
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

<!-- Modal for creating a new client -->
@include('backend.admin.inventory.client.client_modal')

@endsection
@push('js')
<script>
    $('.select2').select2();
    $(document).ready(function () {
        $('.select2').select2();

        $('#purchase_id').change(function () {
            var invoiceId = $(this).val();

            if (invoiceId) {
                $.ajax({
                    url: '/admin/purchase/get-invoice-details/' + invoiceId,
                    type: 'GET',
                    success: function (response) {
                        // Show client details
                        if (response.supplier) {
                            $('#client-details-body').html(`
                                <tr>
                                    <td>${response.supplier.name}</td>
                                    <td>${response.supplier.company}</td>
                                    <td>${response.supplier.phone}</td>
                                    <td>${response.supplier.email}</td>
                                </tr>
                            `);
                            $('#client-details-table').show();
                        }

                        // Clear previous product data
                        $('#product-table tbody').html('');

                        // Show product details
                        if (response.products.length > 0) {
                            response.products.forEach(function (product, index) {
                                $('#product-table tbody').append(`
                                    <tr>
                                        <td>${product.name}</td>
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control quantity" value="${product.quantity}" min="1" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="receive_quantity[]" class="form-control receive-quantity" value="0" min="1" data-available="${product.quantity}">
                                        </td>
                                        <input type="hidden" name="product_id[]" value="${product.id}">
                                    </tr>
                                `);
                            });
                        } else {
                            $('#product-table tbody').html('<tr><td colspan="6" class="text-center">No products found</td></tr>');
                        }
                    },
                    error: function () {
                        toastr.error("Failed to load invoice details. Please try again.");
                        $('#client-details-body').html('');
                        $('#client-details-table').hide();
                        $('#product-table tbody').html('<tr><td colspan="6" class="text-center">No products found</td></tr>');
                    }
                });
            } else {
                toastr.error("Failed to load invoice details. Please try again.");
                $('#client-details-body').html('');
                $('#client-details-table').hide();
                $('#product-table tbody').html('<tr><td colspan="6" class="text-center">No products found</td></tr>');
            }
        });

        // Alert when receive_quantity exceeds available quantity
        $(document).on('input', '.receive-quantity', function () {
            var receiveQty = parseInt($(this).val()) || 0;
            var availableQty = parseInt($(this).data('available')) || 0;

            if (receiveQty > availableQty) {
                toastr.error('Received quantity cannot be greater than available quantity!.', {
                    closeButton: true,
                    progressBar: true,
                    timeOut: 5000
                });
                // alert("Received quantity cannot be greater than available quantity!");
                $(this).val(availableQty); // Reset to max available quantity
            }
        });
    });
</script>
@endpush
