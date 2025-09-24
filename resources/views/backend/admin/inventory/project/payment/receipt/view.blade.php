@extends('layouts.admin', ['pageTitle' => 'Receive Payment List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
                                    <a href="{{ route('project.receipt.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-4">
                                <button class="btn btn-primary" onclick="printInvoice()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Project Name</th>
                                            <td>{{ $project->project_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>{{ $project->project_location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Coordinator</th>
                                            <td>{{ $project->project_coordinator }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer</th>
                                            <td>{{ $project->client->name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Reference No</th>
                                            <td>{{ $project->reference_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Schedule Date</th>
                                            <td>{{ $project->schedule_date ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Project Type</th>
                                            <td>{{ ucfirst($project->project_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $project->description ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terms & Conditions</th>
                                            <td>{!! $project->terms_conditions ?? '' !!}</td>
                                        </tr>
                                        {{-- <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($project->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($project->status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-info text-dark">Partially Paid</span>
                                                @endif
                                            </td>                                        
                                        </tr> --}}
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="card-header">
                                    {{-- <h4>Project Items</h4> --}}
                                </div>
                                <div class="card-body">
                                    {{-- <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Item Name</th>
                                                <th>Order Unit</th>
                                                <th>Unit Price</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $subtotal = 0;
                                                $grandTotal = 0;
                                            @endphp
                                            @foreach ($project->items as $index => $item)
                                                @php
                                                $productTotal = ($item->unit_price * $item->quantity) - $item->discount;
                                                $subtotal += $productTotal;
                                                $grandTotal = $subtotal - $project->total_discount + $project->transport_cost + $project->carrying_charge + $project->vat + $project->tax;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->items }}</td>
                                                    <td>{{ $item->unit->name ?? '' }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->subtotal, 2) }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->discount, 2) }}</td>
                                                    <td>{{ bdt() }} {{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table> --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Unit Price</th>
                                                    <th>Sell Price</th>
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
                                                    <td>{{ number_format($product->pivot->price, 2) }}</td>
                                                    <td>{{ $product->pivot->quantity }}</td>
                                                    <td>{{ number_format($productTotal, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                </div> 
                                <div class="col-6">
                                    {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                            
                                    <div class="table-responsive ">
                                        {{-- <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ bdt() }} {{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Discount:</th>
                                                <td>{{ bdt() }} {{ number_format($project->total_discount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>VAT:</th>
                                                <td>{{ bdt() }} {{ number_format($project->vat, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>TAX:</th>
                                                <td>{{ bdt() }} {{ number_format($project->tax, 2) }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>Total:</th>
                                                <td>{{ bdt() }} {{ number_format($project->grand_total, 2) }}</td>
                                            </tr>
                                        </table> --}}
                                        <div class="table-responsive">
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
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="invoice p-3 mb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 style="text-align: right;">
                                                <img 
                                                    src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                                                    alt="Company Logo" 
                                                    style="height: 40px; vertical-align: middle; margin-right: 10px;"
                                                >
                                            </h4>                                    
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                                <strong>{{ get_company()->name ?? '' }}</strong><br>
                                                {{ get_company()->address ?? '' }}<br>
                                                Phone: {{ get_company()->phone ?? '' }}<br>
                                                Email: {{ get_company()->email ?? '' }}
                                            </address>
                                        </div>
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                                <strong>{{ $project->client->name }}</strong><br>
                                                {{ $project->client->address }}, {{ $project->client->city }}<br>
                                                {{ $project->client->region }}, {{ $project->client->country }}<br>
                                                Phone: {{ $project->client->phone }}<br>
                                                Email: {{ $project->client->email }}
                                            </address>
                                        </div>
                                        <div class="col-sm-4 invoice-col">
                                            <b>Reference No :- {{ $project->reference_no ?? '' }}</b><br>
                                        </div>
                                    </div>

                                    <br>
                                    <br>
                                </div>
                                <div class="card-header">
                                    <h4>Receive Payment</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Invoice No</th>
                                                    <th>Total Amount</th>
                                                    <th>Receive Amount</th>
                                                    <th>Due Amount</th>
                                                    <th>Receive Method</th>
                                                    <th>Receive Date</th>
                                                    <!-- Additional columns for Bank payment -->
                                                    <th>Bank Account No</th>
                                                    <th>Cheque No</th>
                                                    <th>Cheque Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalPaid = 0;
                                                @endphp
                                                @foreach ($project_receipts as $index => $receipt)
                                                    @php
                                                        $pay_amount = $receipt->pay_amount;
                                                        $totalPaid += $pay_amount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $receipt->invoice_no }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->total_amount, 2) }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->pay_amount, 2) }}</td>
                                                        <td>{{ bdt() }} {{ number_format($receipt->due_amount, 2) }}</td>                                                    
                                                        <td>{{ ucfirst($receipt->payment_method) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($receipt->payment_date)->format('d F Y') }}</td>
                                
                                                        <!-- Bank Account No, Cheque No, and Cheque Date only show for "Bank" payment method -->
                                                        @if($receipt->payment_method === 'bank')
                                                            <td>{{ $receipt->bank_account_no ?? 'N/A' }}</td>
                                                            <td>{{ $receipt->cheque_no ?? 'N/A' }}</td>
                                    
                                                            <td>{{ \Carbon\Carbon::parse($receipt->cheque_date)->format('d F Y') }}</td>
                                                        @else
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            

                            <hr>
                            
                            <div class="row">
                                <div class="col-6">
                                </div> 
                                <div class="col-6">
                            
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th>Total Paid :</th>
                                                <td>{{ bdt() }} {{ number_format($totalPaid, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount need to Paid :</th>
                                                <td>{{ bdt() }} {{ number_format($sale->grand_total - $sale->paid_amount, 2) }}</td>
                                            </tr>
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

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush