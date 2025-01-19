@extends('layouts.admin', ['pageTitle' => 'Staff List'])

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
                            <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-success rounded-0">
                                <i class="fas fa-plus fa-sm"></i> Add Attendance
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Staff Name</th>
                                    <th>Shift</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> 
                                        <td>{{ isset($attendance->staff->full_name) ? ucwords(strtolower($attendance->staff->full_name)) : 'N/A' }}</td>
                                        <td>{{ isset($attendance->shift->name) ? ucwords(strtolower($attendance->shift->name)) : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->time)->format('h:i A') ?? 'N/A' }}</td>
                                        <td class="col-4">{{ $attendance->location }}</td>
                                        <td>
                                            <!-- View Button -->
                                            <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <a href="{{ route('attendance.delete', $attendance->id) }}" id="delete" class="btn btn-danger btn-sm">
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
