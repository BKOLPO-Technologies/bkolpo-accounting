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
                                            <th>Project Coordinator Name</th>
                                            <th>Total Amount</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Project Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $key => $project)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $project->project_name }}</td>
                                                <td>{{ $project->client->name ?? 'N/A' }}</td>
                                                <td>{{ $project->project_coordinator ?? 'N/A' }}</td>
                                                <td>{{ bdt() }} {{ number_format($project->grand_total, 2) }}</td>
                                                {{-- <td>
                                                    @if($project->status == 'pending')
                                                        <span class="badge bg-danger">Pending</span>
                                                    @elseif($project->status == 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-warning">Partially Paid</span>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if($project->project_type == 'ongoing')
                                                        <span class="badge bg-danger">Ongoing</span>
                                                    @elseif($project->project_type == 'upcoming')
                                                        <span class="badge bg-primary">Upcoming</span>
                                                    @elseif($project->project_type == 'Running')
                                                        <span class="badge bg-info">Running</span>
                                                    @else
                                                        <span class="badge bg-success">Completed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Sale Button -->
                                                    <a href="{{ route('projects.sales', $project->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
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
