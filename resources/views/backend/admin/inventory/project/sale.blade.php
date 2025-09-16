@extends('layouts.admin', ['pageTitle' => 'Project List'])

<style>
    /* @media print {
        #filter-form {
            display: none !important;
        }
    } */
    @media screen {
        .print-only {
            display: none;
        }
    }

    @media print {

        /* Set A4 Page Size */
        @page {
            size: A4 portrait;
            /* or "A4 landscape" */
            margin: 20mm;
            /* Adjust margins */
        }

        .print-only {
            display: block !important;
        }

        /* Ensure Proper Page Breaks */
        .content-wrapper {
            page-break-before: always;
            page-break-after: avoid;
        }

        /* Avoid Cutting Important Sections */
        .invoice {
            page-break-inside: auto;
        }

        /* Ensure Table Stays Within Page */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Add Page Breaks Where Necessary */
        .row-4,
        .row-7,
        .row-8,
        .row-9 {
            page-break-before: always;
        }

        /* Hide Unnecessary Elements */
        .no-print {
            display: none !important;
        }

        /* Improve Readability in Print */
        body {
            font-size: 12px;
            color: black;
        }
    }
</style>

@section('admin')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
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
                                    <h3 class="mb-0 font-weight-bold">{{ $project->project_name ?? 'N/A' }}</h3>
                                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row text-right">
                                    <div class="col-12">
                                        <button class="btn btn-primary ml-3 mb-3" onclick="printBalanceSheet()">
                                            <i class="fa fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                                <!-- Trial Balance Table -->
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">
                                        <img src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}"
                                            alt="Company Logo"
                                            style="height: 40px; vertical-align: middle; margin-right: 10px;">
                                        {{ get_company()->name ?? '' }}
                                    </h2>
                                    <p class="mb-0"><strong>Project Details</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <!-- Small boxes (Stat box) -->
                                <div class="row">
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h3>{{ number_format($totalAmount, 2, '.', ',') }}</h3>

                                                <p>Total Amount</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-bag"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                More info <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h3>{{ number_format($paidAmount, 2, '.', ',') }}</h3>

                                                <p>Paid Amount</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                               <h3>{{ number_format($dueAmount, 2, '.', ',') }}</h3>

                                                <p>Due Amount</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-stats-bars"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    {{-- <div class="col-lg-3 col-6">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>
                                                @if ($project->status == 'pending')
                                                    Pending
                                                @elseif($project->status == 'paid')
                                                   Paid
                                                @else
                                                   Partially Paid
                                                @endif
                                            </h3>
                                            <p>{{ ucwords('Project Status') }}</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div> --}}
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{ $project->project_type }}</h3>

                                                <p>Project Type</p>
                                            </div>
                                            <div class="icon">
                                                <i class="ion ion-pie-graph"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">More info <i
                                                    class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                </div>
                            </div>

                            <hr />

                            <div id="printable-area">

                                <h4 class="ml-3 mb-0 print-only">{{ $pageTitle ?? 'N/A' }}</h4>

                                {{-- Project details --}}
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
                                                <td>{{ $project->client->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Reference No</th>
                                                <td>{{ $project->reference_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Schedule Date</th>
                                                <td>{{ $project->schedule_date ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Project Type</th>
                                                <td>{{ ucfirst($project->project_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <td>{{ $project->description ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Terms & Conditions</th>
                                                <td>{!! $project->terms_conditions ?? '' !!}</td>
                                            </tr>
                                            {{-- <tr>
                                            <th>Status</th>
                                            <td>
                                                @if ($project->status == 'pending')
                                                    <span class="badge bg-danger">Pending</span>
                                                @elseif($project->status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @else
                                                    <span class="badge bg-warning">Partially Paid</span>
                                                @endif
                                            </td>
                                        </tr> --}}
                                        </table>
                                    </div>
                                </div>
                                {{-- End Project details --}}

                                <hr />

                                <form action="{{ route('projects.sales', ['id' => $project->id]) }}" method="GET"
                                    class="mb-3">
                                    <div class="row justify-content-center">
                                        <div class="col-md-3 mt-3">
                                            <label for="from_date">From Date:</label>
                                            <input type="text" name="from_date" id="from_date" class="form-control"
                                                value="{{ request('from_date', $fromDate) }}">
                                        </div>
                                        <div class="col-md-3 mt-3">
                                            <label for="to_date">To Date:</label>
                                            <input type="text" name="to_date" id="to_date" class="form-control"
                                                value="{{ request('to_date', $toDate) }}">
                                        </div>
                                        <div class="col-md-1 mt-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                                        </div>
                                        <div class="col-md-1 mt-3 d-flex align-items-end">
                                            <a href="{{ route('projects.sales', ['id' => $project->id]) }}"
                                                class="btn btn-danger w-100">Clear</a>
                                        </div>
                                    </div>
                                </form>


                                {{-- Start Expense List --}}
                                <div class="mt-4">
                                    <div class="col-lg-12">
                                        <div class="card ">
                                            <div class="card-header py-2">
                                                <h4 class="mb-0">Expense</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="example12" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Reference No</th>
                                                                <th>Reference Date</th>
                                                                <th>Subtotal</th>
                                                                <th>Discount</th>
                                                                <th>Total</th>
                                                                <th>Paid Amount</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $total = 0;
                                                                $paidAmount = 0;
                                                            @endphp
                                                            {{-- @foreach ($project->purchases as $index => $purchase) --}}
                                                            @foreach ($purchases as $index => $purchase)
                                                                @php
                                                                    $productTotal = $purchase->total;
                                                                    $total += $productTotal;
                                                                    $individualPaidAmount = $purchase->paid_amount;
                                                                    $paidAmount += $individualPaidAmount;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $purchase->invoice_no }}</td>
                                                                    <td>{{ $purchase->invoice_date }}</td>
                                                                    <td>{{ $purchase->subtotal }}</td>
                                                                    <td>{{ $purchase->discount }}</td>
                                                                    <td>{{ $purchase->total }}</td>
                                                                    <td>{{ $purchase->paid_amount }}</td>
                                                                    <td>{{ $purchase->status }}</td>
                                                                    <td>
                                                                        {{-- <button class="btn btn-success" type="button" id="purchaseDetailsBtn" data-toggle="modal" data-target="#purchaseDetailsModal" data-id="{{ $purchase->id }}"> --}}
                                                                        <button class="btn btn-success purchaseDetailsBtn"
                                                                            type="button" data-toggle="modal"
                                                                            data-target="#purchaseDetailsModal"
                                                                            data-id="{{ $purchase->id }}">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                    </div>
                                    <div class="col-6">

                                        <br />

                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Total:</th>
                                                    <td>{{ bdt() }} {{ number_format($total, 2) }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Paid Amount:</th>
                                                    <td>{{ bdt() }} {{ number_format($paidAmount, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Amount in Words: Bottom Left with margin -->
                                    <div class="pl-3" style="margin-top: 20px;">
                                        <strong>Amount in Words:</strong>
                                        <strong class="text-uppercase">{{ convertNumberToWords($paidAmount) }}</strong>
                                    </div>
                                </div>
                                {{-- End Expense List --}}

                                {{-- Start Sales List --}}
                                <div class="mt-4">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header py-2">
                                                <h4 class="mb-0">Sales</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="example13" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Invoice No</th>
                                                                <th>Invoice Date</th>
                                                                <th>Subtotal</th>
                                                                <th>Discount</th>
                                                                <th>Total</th>
                                                                <th>Paid Amount</th>
                                                                <th>Status</th>
                                                                {{-- <th>Action</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $saleTotal = 0;
                                                                $salePaidAmount = 0;
                                                            @endphp
                                                            @foreach ($sales as $index => $sale)
                                                                @php
                                                                    $saleTotal += $sale->total ?? 0;
                                                                    $salePaidAmount += $sale->paid_amount ?? 0;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $sale->invoice_no }}</td>
                                                                    <td>{{ $sale->invoice_date }}</td>
                                                                    <td>{{ $sale->subtotal }}</td>
                                                                    <td>{{ $sale->discount }}</td>
                                                                    <td>{{ $sale->total }}</td>
                                                                    <td>{{ $sale->paid_amount }}</td>
                                                                    <td>{{ ucfirst($sale->status) }}</td>
                                                                    {{-- <td>
                                                                        <button class="btn btn-success saleDetailsBtn"
                                                                            type="button" data-toggle="modal"
                                                                            data-target="#saleDetailsModal"
                                                                            data-id="{{ $sale->id }}">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                    </td> --}}
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <br />
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%">Total Sales:</th>
                                                    <td>{{ bdt() }} {{ number_format($saleTotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Paid Amount:</th>
                                                    <td>{{ bdt() }} {{ number_format($salePaidAmount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Due Amount:</th>
                                                    <td>{{ bdt() }}
                                                        {{ number_format($saleTotal - $salePaidAmount, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Amount in Words -->
                                    <div class="pl-3" style="margin-top: 20px;">
                                        <strong>Amount in Words:</strong>
                                        <strong
                                            class="text-uppercase">{{ convertNumberToWords($salePaidAmount) }}</strong>
                                    </div>
                                </div>
                                {{-- End Sales List --}}


                                {{-- project item --}}
                                {{-- <div class="mt-4">
                                <div class="card-header">
                                    <h4>Project Items</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
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
                                                    <td>{{ $item->order_unit }}</td>
                                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->subtotal, 2) }}</td>
                                                    <td>{{ number_format($item->discount, 2) }}</td>
                                                    <td>{{ number_format($item->total, 2) }}</td>
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
                            
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ bdt() }} {{ number_format($subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Discount:</th>
                                                <td>{{ bdt() }} {{ number_format($project->total_discount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Transport Cost:</th>
                                                <td>{{ bdt() }} {{ number_format($project->transport_cost, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Carrying/Labour Charge:</th>
                                                <td>{{ bdt() }} {{ number_format($project->carrying_charge, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Vat:</th>
                                                <td>{{ bdt() }} {{ number_format($project->vat, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tax:</th>
                                                <td>{{ bdt() }} {{ number_format($project->tax, 2) }}</td>
                                            </tr>
                                            
                                            <tr>
                                                <th>Total:</th>
                                                <td>{{ bdt() }} {{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div> --}}
                                {{-- end project item --}}

                                {{-- <hr> --}}

                                {{-- <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="card ">
                                        <div class="card-header py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="mb-0">Payment Receive</h4>
                                                <a href="{{ route('project.receipt.payment.create') }}" class="btn btn-sm btn-success rounded-0">
                                                    <i class="fas fa-plus fa-sm"></i> Add New Payment Receive
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table id="example12" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No</th>
                                                        <th>Invoice No</th>
                                                        <th>Customer Name</th>
                                                        <th>Pay Amount</th>
                                                        <th>Payment Method</th>  
                                                        <th>Payment Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($project->receipts as $index => $receipt)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $receipt->invoice_no ?? 'N/A' }}</td>
                                                            <td>{{ $receipt->client->name ?? 'N/A' }}</td>
                                                            <td>{{ number_format($receipt->pay_amount, 2) }}</td>
                                                            <td>
                                                                @if ($receipt->payment_method == 'cash')
                                                                    Cash
                                                                @elseif($receipt->payment_method == 'bank')
                                                                    Bank
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                            <td>{{ $receipt->payment_date }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            </div>

                            <hr />



                        </div>
                    </div>
                </div>
            </div>

            <br />
            <br />
        </section>
    </div>

    <!-- Modal for creating a new supplier -->
    @include('backend.admin.inventory.project.saleDetails')
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

    <script>
        $(document).ready(function() {
            // When the "View" button is clicked
            // $('#purchaseDetailsBtn').on('click', function() {
            $(document).on('click', '.purchaseDetailsBtn', function() {

                var purchaseId = $(this).data('id'); // Get the purchase ID
                var url = '/purchase-details/' + purchaseId; // Create the URL for the AJAX request

                // Make an AJAX request to fetch purchase details
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        // Update the modal body with the response
                        // $('#purchaseDetailsBody').html(response);
                        // Log the response to check what you are getting back
                        // console.log(response);

                        if (response.html) {
                            $('#purchaseDetailsBody').html(response.html);
                        } else {
                            console.log('Error in response:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                });
            });
        });
    </script>
@endpush
