@extends('layouts.admin', ['pageTitle' => 'Work Order List'])

@section('admin')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

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
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('workorders.create') }}" class="btn btn-sm btn-success rounded-0">
                                    <i class="fas fa-plus fa-sm"></i> Add New Work Order
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Quotation ID</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workorders as $index => $workorder)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $workorder->quotation->quotation_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($workorder->start_date)->format('d F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($workorder->end_date)->format('d F Y') }}</td>
                                        <td>
                                            @if($workorder->status == 'Completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($workorder->status == 'Cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-warning">In Progress</span>
                                            @endif
                                        </td>
                                        <td>{{ $workorder->remarks ?? 'N/A' }}</td>
                                        <td>{{ $workorder->created_at->format('d M, Y') }}</td>
                                        <td class="col-2">
                                            <a href="{{ route('workorders.invoice', $workorder->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            <a href="{{ route('workorders.show', $workorder->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('workorders.edit', $workorder->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('workorders.destroy', $workorder->id) }}" class="btn btn-danger btn-sm" id="delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
    </section>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#example1').DataTable();
    });
</script>
@endpush
