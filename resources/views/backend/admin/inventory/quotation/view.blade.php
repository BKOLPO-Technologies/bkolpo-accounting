@extends('layouts.admin', [$pageTitle => 'Quotation View'])

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
                                <a href="{{ route('quotations.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Client Name</th>
                                        <td>{{ $quotation->client->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount</th>
                                        <td>৳ {{ number_format($quotation->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Quotation Date</th>
                                        <td>{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($quotation->status === 'Approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($quotation->status === 'Pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $quotation->description ?? 'N/A' }}</td>
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
