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
                                                {{-- <th>Unit Price</th> --}}
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                {{-- <th>Discount</th>
                                                <th>Final Total</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $subtotal = 0;
                                            $totalDiscount = 0; // For the overall discount across all products
                                        @endphp
                                        @foreach ($sale->products as $product)
                                            @php
                                                // Calculate the product's total price
                                                $productTotal = $product->pivot->quantity * $product->pivot->price;
                                                $subtotal += $productTotal;
                                                
                                                // Assume there is a 'discount' field in the pivot table or product
                                                $productDiscount = !empty($product->pivot->discount) ? $product->pivot->discount : 0; // Set discount to 0 if not available
                                                $totalDiscount += $productDiscount;
                            
                                                // Calculate the final total for the product after discount
                                                $finalTotal = $productTotal - $productDiscount;
                                            @endphp
                                            <tr data-product-id="{{ $product->id }}">
                                                <td>{{ $product->name }}</td>
                                                <td>{{ number_format($product->price, 2) }}</td>
                                                {{-- <td>{{ number_format($product->pivot->price, 2) }}</td> --}}
                                                <td>{{ $product->pivot->quantity }}</td>
                                                <td>{{ number_format($productTotal, 2) }}</td>
                                                {{-- <td>{{ number_format($productDiscount, 2) }}</td>
                                                <td>{{ number_format($finalTotal, 2) }}</td> --}}
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <div class="row w-100">
                                    <div class="col-12 col-lg-6 mb-2">
                                    </div>
                                    <div class="col-12 col-lg-6 mb-2">
                                        {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                                    
                                        {{-- <div class="table-responsive">
                                            <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ bdt() }} {{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Discount:</th>
                                                <td>{{ bdt() }} {{ number_format($totalDiscount + (float) $sale->discount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td>{{ bdt() }} {{ number_format($sale->total, 2) }}</td>
                                            </tr>
                                            </table>
                                        </div> --}}

                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><label for="subtotal">Total Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ $subtotal }}" readonly />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_discount">Discount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_discount" name="discount" class="form-control" step="0.01" placeholder="Enter Discount" value="{{ $sale->discount }}" oninput="updateTotal()" readonly/>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label for="total_netamount">Net Amount</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="number" id="total_netamount" name="total_netamount" class="form-control" step="0.01" placeholder="0.00" value="{{ $sale->total_netamount }}" readonly/>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">
                                                            {{-- <input type="checkbox" name="include_tax" id="include_tax" {{ $sale->tax > 0 ? 'checked' : '' }}> --}}

                                                            <input type="checkbox" 
                                                                {{ $sale->tax > 0 ? 'checked' : '' }} 
                                                                onclick="return false;">
                                                            

                                                            <label for="include_tax" class="me-3">
                                                                Include TAX (%)
                                                                <input type="number" name="tax" id="tax" value="{{ $sale->tax }}" min="0"
                                                                    class="form-control form-control-sm d-inline-block"
                                                                    step="0.01" placeholder="Enter TAX"
                                                                    style="width: 100px; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 mb-3 tax-fields">
                                                            <input type="text" id="tax_amount" name="tax_amount" value="{{ $sale->tax_amount }}" class="form-control" readonly placeholder="TAX Amount" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        <div class="icheck-success d-inline">

                                                            <input type="checkbox"
                                                                {{ $sale->vat > 0 ? 'checked' : '' }} 
                                                                onclick="return false;" />

                                                            <label for="include_vat">
                                                                Include VAT (%)
                                                                <input type="number" id="vat" name="vat" value="{{ $sale->vat }}" min="0"
                                                                    class="form-control form-control-sm vat-input"
                                                                    step="0.01" placeholder="Enter VAT"
                                                                    style="width: 70px; display: inline-block; margin-left: 10px;" disabled />
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="col-12 col-lg-12 vat-fields">
                                                            <input type="text" id="vat_amount" name="vat_amount" value="{{ $sale->vat_amount }}" class="form-control" readonly placeholder="VAT Amount" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td><label for="grand_total">Grand Total</label></td>
                                                    <td>
                                                        <div class="col-12 col-lg-12">
                                                            <input type="text" id="grand_total" name="grand_total" class="form-control" value="{{ $sale->grand_total }}" readonly/>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
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