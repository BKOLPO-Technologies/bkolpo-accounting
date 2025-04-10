{{-- Based on category and product --}}
@extends('layouts.admin', ['pageTitle' => 'Purchase List'])
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
        .col-lg-4 {
            float: right !important;
            width: 33.3333% !important;
        }
        .col-lg-8 {
            float: left !important;
            width: 66.6667% !important;
        }
        .table td, .table th {
            border: 1px solid black !important;
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
                                <a href="{{ route('admin.sale.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <!-- Print Button -->
                        <div class="text-right mt-3 mr-4">
                            <button class="btn btn-primary" onclick="printBalanceSheet()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                        <div id="printable-area">
                        <div class="card-body">

                        <div class="invoice p-3 mb-3">
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <img 
                                            src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                                            alt="Company Logo" 
                                            style="height: 40px; vertical-align: middle; margin-right: 10px;"
                                        >
                                        {{ get_company()->name ?? '' }}
                                        <small class="float-right" id="current-date"></small>
                                    </h4>                                    
                                </div>
                            </div>

                            <hr>

                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                Owner
                                <address>
                                    <strong>{{ get_company()->name ?? '' }}</strong><br>
                                    {{ get_company()->address ?? '' }}<br>
                                    Phone: {{ get_company()->phone ?? '' }}<br>
                                    Email: {{ get_company()->email ?? '' }}
                                </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                Client
                                <address>
                                    <strong>{{ $sale->client->name }}</strong><br>
                                    {{ $sale->client->address }}, {{ $sale->client->city }}<br>
                                    {{ $sale->client->region }}, {{ $sale->client->country }}<br>
                                    Phone: {{ $sale->client->phone }}<br>
                                    Email: {{ $sale->client->email }}
                                </address>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                <b>Invoice :- {{ $sale->invoice_no }}</b><br>
                                <br>
                                </div>
                            </div>

                            <br>
                            <br>

                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid black; padding: 8px;">Product</th>
                                                <th style="border: 1px solid black; padding: 8px;">Price</th>
                                                <th style="border: 1px solid black; padding: 8px;">Quantity</th>
                                                <th style="border: 1px solid black; padding: 8px;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $subtotal = 0;
                                                $totalDiscount = 0;
                                            @endphp
                                            @foreach ($sale->products as $product)
                                                @php
                                                    $productTotal = $product->pivot->quantity * $product->pivot->price;
                                                    $subtotal += $productTotal;
                                                    
                                                    $productDiscount = !empty($product->pivot->discount) ? $product->pivot->discount : 0;
                                                    $totalDiscount += $productDiscount;
                                
                                                    $finalTotal = $productTotal - $productDiscount;
                                                @endphp
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td style="border: 1px solid black; padding: 8px;">{{ $product->name }}</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($product->price, 2) }}</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ $product->pivot->quantity }}</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($productTotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                    </div>
                                    <div class="col-lg-4">

                                        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 20px;">
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;">Total Amount</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($subtotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;">Discount</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($sale->discount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;">Net Amount</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($sale->total_netamount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;">TAX ({{ $sale->tax }}%)</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($sale->tax_amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;">VAT ({{ $sale->vat }}%)</td>
                                                    <td style="border: 1px solid black; padding: 8px;">{{ number_format($sale->vat_amount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 8px;"><strong>Grand Total</strong></td>
                                                    <td style="border: 1px solid black; padding: 8px;"><strong>{{ number_format($sale->grand_total, 2) }}</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                                {{-- <table style="width: 100%; border-collapse: collapse; margin-top: 30px;" border="1">
                                    <tr>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <small style="font-weight:bolder;">Prepare by : (Department Manager)</small>
                                            <p style="border-bottom: 2px dotted black; margin: 30px 10 10 10;"></p>
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <small style="font-weight:bolder;">Checked by : (Cost Manager)</small>
                                            <p style="border-bottom: 2px dotted black; margin: 30px 10 10 10;"></p>
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <small style="font-weight:bolder;">Approved by : (Co-Project Manager)</small>
                                            <p style="border-bottom: 2px dotted black; margin: 30px 10 10 10;"></p>
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <small style="font-weight:bolder;">Received by : (Vendor / Subcontractor)</small>
                                            <p style="border-bottom: 2px dotted black; margin: 30px 10 10 10;"></p>
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <td style="padding: 10px; text-align: center; border-bottom: 1px dotted black;">
                                            Date: <span style="display: inline-block; width: 80%;margin: 20px 0 0 0; border-bottom: 2px dotted black;"></span>
                                        </td>
                                        <td style="padding: 10px; text-align: center; border-bottom: 1px dotted black;">
                                            Date: <span style="display: inline-block; width: 80%;margin: 20px 0 0 0; border-bottom: 2px dotted black;"></span>
                                        </td>
                                        <td style="padding: 10px; text-align: center; border-bottom: 1px dotted black;">
                                            Date: <span style="display: inline-block; width: 80%;margin: 20px 0 0 0; border-bottom: 2px dotted black;"></span>
                                        </td>
                                        <td style="padding: 10px; text-align: center; border-bottom: 1px dotted black;">
                                            Date: <span style="display: inline-block; width: 80%;margin: 20px 0 0 0; border-bottom: 2px dotted black;"></span>
                                        </td>
                                    </tr>
                                </table> --}}
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