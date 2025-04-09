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
                                <a href="{{ route('admin.sale.index')}}" class="btn btn-sm btn-danger rounded-0">
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
                                    <i class="fas fa-globe"></i> Bkolpo Construction Ltd.
                                    <small class="float-right" id="current-date"></small>
                                </h4>
                                </div>
                            </div>

                            <hr>

                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                Owener
                                <address>
                                    <strong>Bkolpo Construction Ltd.</strong><br>
                                    Tokyo tower<br>
                                    Tongi, Gazipur, Dhaka<br>
                                    Phone: (804) 123-5432<br>
                                    Email: info@almasaeedstudio.com
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
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
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
                                                <td>{{ $product->name }}</td>
                                                <td>{{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->pivot->quantity }}</td>
                                                <td>{{ number_format($productTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <div class="row w-100">
                                    <div class="col-8 col-lg-6">
                                    </div>
                                    <div class="col-4 col-lg-6">

                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td>Total Amount</td>
                                                    <td>{{ $subtotal }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Discount</td>
                                                    <td>{{ $sale->discount }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td>{{ $sale->total_netamount }}</td>
                                                </tr>

                                                <tr>
                                                    <td>TAX ({{ $sale->tax }}%)</td>
                                                    <td>{{ $sale->tax_amount }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>VAT ({{ $sale->vat }}%)</td>
                                                    <td>{{ $sale->vat_amount }}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>Grand Total</td>
                                                    <td>{{ $sale->grand_total }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <table style="width: 100%; border-collapse: collapse; margin-top: 30px;" border="1">
                                    <tr>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <strong>Prepare by :</strong><br>
                                            ( Department Manager )<br><br>
                                            Signature: ___________
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <strong>Checked by :</strong><br>
                                            ( Cost Manager )<br><br>
                                            Signature: ___________
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <strong>Approved by :</strong><br>
                                            ( Co-Project Manager )<br><br>
                                            Signature: ___________
                                        </td>
                                        <td style="padding: 10px; text-align: center; width: 25%;">
                                            <strong>Received by :</strong><br>
                                            ( Vendor / Subcontractor )<br><br>
                                            Signature: ___________
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px; text-align: center;">Date: ___________________</td>
                                        <td style="padding: 10px; text-align: center;">Date: ___________________</td>
                                        <td style="padding: 10px; text-align: center;">Date: ___________________</td>
                                        <td style="padding: 10px; text-align: center;">Date: ___________________</td>
                                    </tr>
                                </table>                                                               
                                
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