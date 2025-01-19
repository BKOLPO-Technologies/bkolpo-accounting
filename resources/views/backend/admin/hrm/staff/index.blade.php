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
                                    <a href="{{ route('staff.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Staff
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>HR Code</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Birthday</th>
                                            <th>Sex</th>
                                            <th>Last Login</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($staffs as $index => $staff) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $staff->hr_code }}</td>
                                                <td>
                                                    <a href="#" class="text-decoration-none img-fluid rounded">
                                                        <img src="{{ (!empty($staff->profile_image)) ? url('upload/staff/'.$staff->profile_image):url('https://via.placeholder.com/70x60') }}" 
                                                             class="staff-profile-image-small" 
                                                             alt="Charles Smith's Profile Image" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;">
                                                        {{ $staff->full_name }}
                                                    </a>
                                                 
                                                </td>
                                                <td>{{ $staff->email }}</td>
                                                <td>{{ \Carbon\Carbon::parse($staff->birthday)->format('d-m-Y') }}</td>
                                                <td>{{ $staff->sex }}</td>
                                                <td>{{ \Carbon\Carbon::parse($staff->last_login)->format('j F Y h:i A') }}</td>
                                                <td>
                                                    @if($staff->status === 'Working')
                                                        <a href="#" class="badge badge-success">
                                                            <span class="badge bg-success">Working</span>
                                                        </a>
                                                    @elseif($staff->status === 'Maternity')
                                                        <a href="#" class="badge badge-warning">
                                                            <span class="badge bg-warning">Maternity</span>
                                                        </a>
                                                    @elseif($staff->status === 'Inactivity')
                                                        <a href="#" class="badge badge-danger">
                                                            <span class="badge bg-danger">Inactivity</span>
                                                        </a>
                                                    @else
                                                        <a href="#" class="badge badge-secondary">
                                                            <span class="badge bg-secondary">Unknown</span>
                                                        </a>
                                                    @endif
                                                </td>                                                
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('staff.show',$staff->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="{{ route('staff.edit',$staff->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <a href="{{ route('staff.delete',$staff->id)}}" id="delete" class="btn btn-danger btn-sm">
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
