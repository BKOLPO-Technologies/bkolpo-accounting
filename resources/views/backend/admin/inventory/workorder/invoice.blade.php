@extends('layouts.admin', [$pageTitle => 'Work Order Invoice'])

@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1 class="text-center">{{ $pageTitle }}</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-primary shadow-lg">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ !empty($workorder->logo) ? url('upload/company/' . $workorder->logo) : url(asset('backend/logo.jpg')) }}" alt="Company Logo" height="50">
                            <h3 class="ml-3 mb-0">Bkolpo Technology</h3>
                        </div>
                        <a href="{{ route('workorders.index') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5><strong>Client Details</strong></h5>
                            <p>
                                Name: {{ $workorder->quotation->client->name ?? 'N/A' }} <br>
                                Address: {{ $workorder->quotation->client->address ?? 'N/A' }} <br>
                                Phone: {{ $workorder->quotation->client->phone ?? 'N/A' }} <br>
                                Email: {{ $workorder->quotation->client->email ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-6 text-right">
                            <h5><strong>Work Order Details</strong></h5>
                            <p>
                                Work Order Date: {{ \Carbon\Carbon::parse($workorder->work_order_date)->format('d M, Y') }} <br>
                                Status: 
                                @if($workorder->status === 'Completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($workorder->status === 'In Progress')
                                    <span class="badge bg-warning">In Progress</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Sl</th>
                                <th>Quotation</th>
                                <th>Description</th>
                                <th class="text-center">Amount (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $workorder->quotation->quotation_number ?? 'N/A' }}</td>
                                <td>{{ $workorder->quotation->description ?? 'N/A' }}</td>
                                <td class="text-center">৳ {{ number_format($workorder->quotation->total_amount ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total Amount:</th>
                                <th class="text-center">৳ {{ number_format($workorder->quotation->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="mt-4">
                        <h5><strong>Remarks:</strong></h5>
                        <p>{{ $workorder->remarks ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-secondary" onclick="window.print();">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
