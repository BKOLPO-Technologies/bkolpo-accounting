@extends('layouts.admin', [$pageTitle => 'Work Order View'])

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
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('workorders.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Quotation Number</th>
                                        <td>{{ $workorder->quotation->quotation_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client Name</th>
                                        <td>{{ $workorder->quotation->client->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount</th>
                                        <td>৳ {{ number_format($workorder->quotation->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Start Date</th>
                                        <td>{{ \Carbon\Carbon::parse($workorder->start_date)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>End Date</th>
                                        <td>{{ \Carbon\Carbon::parse($workorder->end_date)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($workorder->status === 'In Progress')
                                                <span class="badge bg-info">In Progress</span>
                                            @elseif($workorder->status === 'Completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td>{{ $workorder->remarks ?? 'N/A' }}</td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
