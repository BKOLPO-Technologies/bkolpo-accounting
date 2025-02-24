@extends('layouts.admin', ['pageTitle' => 'Purchase List'])
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }
</style>
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
                                
                            </div>
                        </div>
                        <div id="printable-area">
                        <div class="card-body">

                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> Bkolpo, Technology.
                                    <!-- <small class="float-right">Date: 2/10/2014</small> -->
                                    <small class="float-right" id="current-date"></small>
                                </h4>
                                </div>
                            </div>

                            <hr>

                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                Owener
                                <address>
                                    <strong>Bkolpo, Technology.</strong><br>
                                    Tokyo tower<br>
                                    Tongi, Gazipur, Dhaka<br>
                                    Phone: (804) 123-5432<br>
                                    Email: info@almasaeedstudio.com
                                </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                Supplier
                                <address>
                                    <strong>{{ $purchase->supplier->name }}</strong><br>
                                    {{ $purchase->supplier->address }}, {{ $purchase->supplier->city }}<br>
                                    {{ $purchase->supplier->region }}, {{ $purchase->supplier->country }}<br>
                                    Phone: {{ $purchase->supplier->phone }}<br>
                                    Email: {{ $purchase->supplier->email }}
                                </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                <b>Invoice :- {{ $purchase->invoice_no }}</b><br>
                                <br>
                                </div>
                            </div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Qty</th>
                                                <th>Product</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $subtotal = 0;
                                        @endphp
                                        @foreach ($purchase->products as $product)
                                            @php
                                                $productTotal = $product->pivot->quantity * $product->pivot->price;
                                                $subtotal += $productTotal;
                                            @endphp
                                            <tr data-product-id="{{ $product->id }}">
                                                <td>{{ $product->pivot->quantity }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ number_format($product->pivot->quantity * $product->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-6">
                                </div> 
                                <div class="col-6">
                                <p class="lead">Amount Due 2/22/2014</p>

                                <div class="table-responsive">
                                    <table class="table">
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td>৳{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td>৳{{ number_format($purchase->discount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>৳{{ number_format($subtotal - $purchase->discount, 2) }}</td>
                                    </tr>
                                    </table>
                                </div>
                                </div>
                            </div>

                            <div class="row no-print">
                                <div class="col-12">
                                    
                                    <!-- <a href="{{ route('admin.purchase.print') }}" target="_blank" class="btn btn-default">
                                        <i class="fas fa-print"></i> Print
                                    </a> -->

                                    <button class="btn btn-primary" onclick="printBalanceSheet()">
                                        <i class="fa fa-print"></i> Print
                                    </button>

                                    <!-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                        <i class="fas fa-download"></i> Generate PDF
                                    </button> -->

                                </div>
                            </div>
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

@push('js')
<script>
  const options = { 
    day: '2-digit', 
    month: 'long', 
    year: 'numeric' 
  };
  const currentDate = new Date().toLocaleDateString('en-US', options);
  document.getElementById('current-date').textContent = 'Date: ' + currentDate;

</script>


<script>
    function printBalanceSheet() {
        var printContent = document.getElementById("printable-area").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
@endpush