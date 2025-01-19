@extends('layouts.admin', ['pageTitle' => 'Activity List'])

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
                <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
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
                            <a href="{{ route('activity.create') }}" class="btn btn-sm btn-success rounded-0">
                                <i class="fas fa-plus fa-sm"></i> Add Activity
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Staff Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activitys as $index => $activity)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> 
                                        <td>{{ isset($activity->staff->full_name) ? ucwords(strtolower($activity->staff->full_name)) : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($activity->time)->format('h:i A') ?? 'N/A' }}</td>
                                        <td>{{ $activity->location }}</td>
                                        <td>
                                            <!-- View Button -->
                                            <a href="{{ route('activity.show', $activity->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <a href="{{ route('activity.delete', $activity->id) }}" id="delete" class="btn btn-danger btn-sm">
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
