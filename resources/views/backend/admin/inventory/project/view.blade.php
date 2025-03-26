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
            size: A4 portrait; /* or "A4 landscape" */
            margin: 20mm; /* Adjust margins */
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
        .row-4, .row-7, .row-8, .row-9 {
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
                                <a href="{{ route('projects.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <div id="printable-area">

                            <h4 class="ml-3 mb-0 print-only">{{ $pageTitle ?? 'N/A' }}</h4>

                            <div class="card-body">
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
                                        <th>Client</th>
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
                                        <td>{{ $project->terms_conditions ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge bg-info">{{ ucfirst($project->status) }}</span></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mt-4">
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
                                                    <td>{{ $item->unit->name ?? '' }}</td>
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
                                    {{-- <p class="lead">Amount Due 2/22/2014</p> --}}
                            
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
                            </div>

                        </div>

                        <hr/>

                        <div class="row no-print">
                            <div class="col-12">
                                <button class="btn btn-primary ml-3 mb-3" onclick="printBalanceSheet()">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        

        <br/>
        <br/>
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