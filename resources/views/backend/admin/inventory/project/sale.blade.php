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
                        
                        <div class="card-body">
                            <!-- Small boxes (Stat box) -->
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                        <h3>{{ $totalAmount }}</h3>
                        
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
                                            <h3>{{ $paidAmount }}</h3>
                        
                                        <p>Paid Amount</p>
                                        </div>
                                        <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                        <h3>{{ $project->status }}</h3>
                        
                                        <p>Project Status</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
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
                                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                        </div>

                        <hr/>
                        
                        <form action="{{ route('report.trial.balance') }}" method="GET" class="mb-3">
                            <div class="row justify-content-center">
                                <div class="col-md-3 mt-3">
                                    <label for="from_date">From Date:</label>
                                    <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="to_date">To Date:</label>
                                    <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                </div>
                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                    <a href="{{ route('report.trial.balance') }}"  class="btn btn-danger w-100">Clear</a>
                                </div>
                            </div>
                        </form>

                        <hr/>

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
                                    <h4>Sales list</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Subtotal</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                                <th>Paid Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                $paidAmount = 0;
                                            @endphp
                                            @foreach ($project->sales as $index => $sale)
                                                @php
                                                $productTotal = $sale->total;
                                                $total += $productTotal;
                                                $individualPaidAmount = $sale->paid_amount;
                                                $paidAmount += $individualPaidAmount;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $sale->invoice_no }}</td>
                                                    <td>{{ $sale->invoice_date }}</td>
                                                    <td>{{ $sale->subtotal }}</td>
                                                    <td>{{ $sale->discount }}</td>
                                                    <td>{{ $sale->total }}</td>
                                                    <td>{{ $sale->paid_amount }}</td>
                                                    <td>{{ $sale->status }}</td>
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
                            
                                    <br/>
                                    
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
                            </div>

                            <hr>
                            
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