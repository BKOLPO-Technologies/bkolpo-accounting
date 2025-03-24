@extends('layouts.admin', ['pageTitle' => 'Project Profit & Loss Report'])
<!-- CSS to Hide Form on Print -->
<style>
    @media print {
        #filter-form {
            display: none !important;
        }
    }
</style>
@section('admin')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
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
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('report.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <!-- Print Button -->
                        <div class="text-right mt-3 mr-4">
                            <button class="btn btn-primary" onclick="printProfitLoss()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>

                        <div id="printable-area">
                            <div class="card-body">
                                <!-- Balance Sheet Table -->
                                <div class="card-header text-center mb-3">
                                    <h2 class="mb-1">{{ get_company()->name ?? '' }}</h2>
                                    <p class="mb-0"><strong>Project Profit & Loss Report</strong></p>
                                    <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                                </div>
                                <div class="card-body">
                                    <!-- Date Filter Form -->
                                    <div id="filter-form">
                                        <form action="{{ route('report.project.profit.loss') }}" method="GET" class="mb-3">
                                            <div class="row justify-content-center">
                                                <div class="col-md-2 mt-3">
                                                    <label for="from_date">From Date:</label>
                                                    <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                                </div>
                                                <div class="col-md-2 mt-3">
                                                    <label for="to_date">To Date:</label>
                                                    <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                                </div>
                                                <!-- Project Selection -->
                                                <div class="col-md-2 mt-3">
                                                    <label for="project_id">Select Project:</label>
                                                    <select name="project_id" id="project_id" class="form-control">
                                                        <option value="">Select a Project</option>
                                                        @foreach ($allProjects as $project)
                                                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                                                {{ $project->project_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>                                                    
                                                </div>
                                                <!-- Filter Button -->
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                                </div>

                                                <!-- Clear Button -->
                                                <div class="col-md-1 mt-3 d-flex align-items-end">
                                                    <a href="{{ route('report.project.profit.loss') }}" class="btn btn-danger w-100">Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                            <!-- Project Profit & Loss Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Project</th>
                                                            <th class="text-right">Total Orders</th>
                                                            <th class="text-right">Total Expense</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="projectTableBody">
                                                        @forelse ($projects as $key => $project)
                                                            @php
                                                                $totalPurchase = $project->purchases->sum('total');
                                                                $profitLoss = $project->grand_total - $totalPurchase;
                                                            @endphp
                                                            <tr class="table-secondary">
                                                                <td><strong>{{ $key + 1 }}</strong></td>
                                                                <td><strong>{{ $project->project_name }}</strong></td>
                                                                <td class="text-right"><strong>{{ bdt() }} {{ number_format($project->grand_total, 2) }}</strong></td>
                                                                <td class="text-right"><strong>{{ bdt() }} {{ number_format($totalPurchase, 2) }}</strong></td>
                                                            </tr>
                                                        @empty
                                                            @if(request('project_id')) 
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No data available for this project</td>
                                                                </tr>
                                                            @endif
                                                        @endforelse
                                                    </tbody>
                                                    
                                                    <tfoot>
                                                        <tr class="table-primary">
                                                            <th colspan="2">Total</th>
                                                            <th class="text-right">{{ bdt() }} {{ number_format($totalSales, 2) }}</th>
                                                            <th class="text-right">{{ bdt() }} {{ number_format($totalPurchases, 2) }}</th>
                                                        </tr>
                                                        <tr class="table-success">
                                                            <th colspan="3">Net Profit / Loss</th>
                                                            <th class="text-right">
                                                                {{ bdt() }} {{ number_format($netProfitLoss, 2) }}
                                                            </th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>                                         
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
<!-- JavaScript for Printing -->
<script>
    function printProfitLoss() {
        // Get the content to print
        var printContent = document.getElementById("printable-area").innerHTML;
        
        // Get the section to hide (Select Project dropdown)
        var projectSelectSection = document.getElementById("project_id");

        // Save the current state of the document body
        var originalContent = document.body.innerHTML;

        // Hide the select project section
        projectSelectSection.style.display = "none";

        // Replace the body content with the printable content
        document.body.innerHTML = printContent;

        // Trigger the print dialog
        window.print();

        // Restore the original content of the page
        document.body.innerHTML = originalContent;
    }
</script>
@endpush
