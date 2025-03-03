@extends('layouts.admin', ['pageTitle' => 'Edit Sales'])
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('incoming.chalan.index') }}" style="text-decoration: none; color: black;">Back to List</a>
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
                            <a href="{{ route('incoming.chalan.index') }}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('incoming.chalan.update', $incomingChalan->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mt-5">
                                <!-- Select Invoice NO -->
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="sale_id">Invoice No</label>
                                    <input type="text" class="form-control" value="{{ $incomingChalan->sale->invoice_no ?? 'N/A' }}" readonly>
                                    <input type="hidden" name="sale_id" value="{{ $incomingChalan->sale_id }}">
                                    
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="invoice_date">Chalan Date</label>
                                    <input type="text" id="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', $incomingChalan->invoice_date) }}" readonly />
                                    
                                </div>
                                
                            </div>

                            <!-- Customer Details Table -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive-sm">
                                        <table class="table table-bordered" id="client-details-table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Client Name</th>
                                                    <th>Company</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                </tr>
                                            </thead>
                                            <tbody id="client-details-body">
                                                <tr>
                                                    <td>{{ $incomingChalan->sale->client->name }}</td>
                                                    <td>{{ $incomingChalan->sale->client->company }}</td>
                                                    <td>{{ $incomingChalan->sale->client->phone }}</td>
                                                    <td>{{ $incomingChalan->sale->client->email }}</td>
                                                </tr>
                                            </tbody>
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
                                                @foreach($incomingChalan->products as $product)
                                                <tr>
                                                    <td>{{ $product->product->name }}</td>
                                                    <td>
                                                        <input type="number" name="quantity[]" class="form-control quantity" value="{{ $product->quantity }}" min="1" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="receive_quantity[]" class="form-control receive-quantity" value="{{ $product->receive_quantity }}" min="1" data-available="{{ $product->quantity }}">
                                                    </td>
                                                    <input type="hidden" name="product_id[]" value="{{ $product->product_id }}">
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Note -->
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="description">Note</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter Note">{{ old('description', $incomingChalan->description) }}</textarea>
                            </div>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-edit"></i> Update</button>
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
    $('.select2').select2();
    $(document).ready(function () {
        $('.select2').select2();

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
