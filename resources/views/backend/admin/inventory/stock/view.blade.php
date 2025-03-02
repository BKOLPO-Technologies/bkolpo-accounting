@extends('layouts.admin', ['pageTitle' => 'Purchase List'])
@section('admin')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                </ol>
            </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('stock.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="invoice p-3 mb-3">
                                <div class="row">
                                    <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> Bkolpo, Technology.
                                        <small class="float-right" id="current-date"></small>
                                    </h4>
                                    </div>
                                </div>

                                <br/>

                                {{-- here --}}

                                <!-- Product Information Section -->
                                <div class="border rounded p-3 bg-light mb-4">
                                    <h5 class="fw-bold text-primary">Product Information</h5>
                                    <p><strong>Name:</strong> {{ $product->name }}</p>
                                    <p><strong>Category:</strong> {{ $product->category->name ?? 'Uncategorized' }}</p>
                                    <p><strong>Description:</strong> {{ $product->description ?? 'No description available' }}</p>
                                </div>

                                <div class="row mt-4 mb-4">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="fw-bold text-success">Total Stock In</h5>
                                            <p><strong>Quantity:</strong> {{ $totalQuantityIn }}</p>
                                            <p><strong>Price:</strong> {{ number_format($totalPriceIn, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <h5 class="fw-bold text-danger">Total Stock Out</h5>
                                            <p><strong>Quantity:</strong> {{ $totalQuantityOut }}</p>
                                            <p><strong>Price:</strong> {{ number_format($totalPriceOut, 2) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Reference Lot</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Transaction Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allRecords as $record)
                                            {{-- <tr class="{{ $record->type == 'in' ? 'table-success' : 'table-danger' }}"> --}}
                                            <tr>
                                                <td>{{ $record->reference_lot }}</td>
                                                <td class="fw-bold text-uppercase">{{ $record->type }}</td>
                                                <td>{{ $record->quantity }}</td>
                                                <td>{{ number_format($record->price, 2) }}</td>
                                                <td>{{ $record->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
