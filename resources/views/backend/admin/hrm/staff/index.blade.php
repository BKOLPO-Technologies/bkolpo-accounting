@extends('layouts.admin', ['pageTitle' => 'Staff List'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('admin.staff.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add Now Staff
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Photo</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staffs as $index => $member)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <!-- Thumbnail -->
                                                    <a href="#" data-toggle="modal" data-target="#profileImageModal">
                                                        <img src="{{ !empty($member->profile_image) ? url($member->profile_image) : 'https://via.placeholder.com/70x60' }}"
                                                            class="staff-profile-image-small"
                                                            alt="{{ $member->name }}'s Profile Image"
                                                            style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                                                    </a>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="profileImageModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="profileImageModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-success">
                                                                    <h5 class="modal-title" id="profileImageModalLabel">
                                                                        {{ $member->name }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" class="text-light">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="{{ !empty($member->profile_image) ? url($member->profile_image) : 'https://via.placeholder.com/300x300' }}"
                                                                        alt="{{ $member->full_name }}'s Profile Image"
                                                                        style="max-width:100%; height:auto; border-radius:10px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>



                                                <td>{{ $member->employee_id ?? '' }}</td>
                                                <td>{{ $member->name ?? '' }}</td>
                                                <td>{{ $member->department ?? '' }}</td>
                                                <td>{{ $member->designation ?? '' }}</td>
                                                <td>{{ $member->email ?? '' }}</td>
                                                <td>{{ $member->phone ?? '' }}</td>
                                                <td>
                                                    @if ($member->status == '1')
                                                        <span class="badge badge-success">Running Employee</span>
                                                    @else
                                                        <span class="badge badge-danger">Left the Employee</span>
                                                    @endif
                                                </td>

                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('admin.staff.show', $member->id) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.staff.edit', $member->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <a href="{{ route('admin.staff.delete', $member->id) }}" id="delete"
                                                        class="btn btn-danger btn-sm">
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
