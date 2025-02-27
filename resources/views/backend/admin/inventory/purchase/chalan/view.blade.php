@extends('layouts.admin', ['pageTitle' => 'Edit Sales'])
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }
</style>
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
                                    Client
                                    <address>
                                        <strong>{{ $chalan->purchase->supplier->name }}</strong><br>
                                        {{ $chalan->purchase->supplier->address }}, {{ $chalan->purchase->supplier->city }}<br>
                                        {{ $chalan->purchase->supplier->region }}, {{ $chalan->purchase->supplier->country }}<br>
                                        Phone: {{ $chalan->purchase->supplier->phone }}<br>
                                        Email: {{ $chalan->purchase->supplier->email }}
                                    </address>
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                    <b>Invoice :- {{ $chalan->purchase->invoice_no }}</b><br>
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
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Receive Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($chalan->products as $product)
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td>{{ $product->product->name }}</td>
                                                    <td>{{ $product->product->quantity }}</td>
                                                    <td>{{ $product->receive_quantity }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row no-print">
                                <div class="col-12">

                                    <button class="btn btn-primary" onclick="printBalanceSheet()">
                                        <i class="fa fa-print"></i> Print
                                    </button>

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