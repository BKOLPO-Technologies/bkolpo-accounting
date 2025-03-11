@extends('layouts.admin', ['pageTitle' => 'Purchased Products History'])

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.supplier.index') }}">Suppliers</a></li>
                        <li class="breadcrumb-item active">Purchased Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $supplier->name }} - Products Purchased</h4>
                        <a href="{{ route('admin.supplier.view',$supplier->id) }}" class="btn btn-sm btn-danger rounded-0">
                            <i class="fa-solid fa-arrow-left"></i> Back To List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL</th> 
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalAmount = 0;
                                    $totalDiscount = 0;
                                    $totalAfterDiscount = 0;
                                    $sl = 1;
                                    $totalPurchaseProducts = 0;
                                @endphp
                                @foreach ($purchasedProducts as $purchase)
                                    @foreach ($purchase->products as $product)
                                        @php
                                            $productAmount = $product->pivot->quantity * $product->pivot->price;
                                            $productDiscount = $product->pivot->discount;
                                            $subtotal = $productAmount - $productDiscount;
                                            $total = $subtotal;
                        
                                            // Accumulate totals
                                            $totalAmount += $productAmount;
                                            $totalDiscount += $productDiscount;
                                            $totalAfterDiscount += $total;
                                            $totalPurchaseProducts += $product->pivot->quantity;
                                        @endphp
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->pivot->quantity }}</td>
                                            <td>{{ bdt() }} {{ number_format($product->pivot->price, 2) }}</td>
                                            <td>{{ bdt() }} {{ number_format($productAmount, 2) }}</td> 
                                            <td>{{ bdt() }} {{ number_format($productDiscount, 2) }}</td> 
                                            <td>{{ bdt() }} {{ number_format($total, 2) }}</td> 
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Total Summary Section -->
                    <div class="row justify-content-end mt-3">
                        <div class="col-md-4">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>Total</th>
                                            <td>{{ bdt() }} {{ number_format($totalAfterDiscount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Discounts</th>
                                            <td>{{ bdt() }} {{ number_format($totalDiscounta, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Purchases Products</th>
                                            <td>{{ bdt() }} {{ number_format($totalPurchaseAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Paid Amount</th>
                                            <td>{{ bdt() }} {{ number_format($totalPaidAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Due Amount</th>
                                            <td>{{ bdt() }} {{ number_format($totalDueAmount, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
