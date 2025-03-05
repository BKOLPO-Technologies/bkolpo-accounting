@extends('layouts.admin', ['pageTitle' => 'Project List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('projects.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Project
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Project Name</th>
                                            <th>Customer Name</th>
                                            <th>Total Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Status</th>
                                            <th>Order Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $project)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($project->created_at)->format('d F Y') }}</td>
                                                <td>{{ $project->name ?? 'N/A' }}</td> 
                                                <td>{{ bdt() }} {{ number_format($project->total_amount ?? 'N/A', 2) }}</td>
                                                <td>{{ bdt() }} {{ number_format($project->paid_amount ?? 'N/A', 2) }}</td> 
                                                <td>{{ bdt() }} {{ number_format($project->paid_amount ?? 'N/A', 2) }}</td> 
                                                <!-- Status column with Badge -->
                                                <td>
                                                    <span class="badge bg-danger">Ongoing</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger">Pending</span>
                                                </td>
                                                
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('projects.destroy', $project->id) }}" id="delete" class="btn btn-danger btn-sm">
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
