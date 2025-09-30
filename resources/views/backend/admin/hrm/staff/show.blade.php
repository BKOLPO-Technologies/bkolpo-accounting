@extends('layouts.admin', [$pageTitle => 'Leave Application Show'])

@section('admin')
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
                    <div class="col-md-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('admin.staff.index') }}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List Staff
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Employee ID</th>
                                            <td>{{ $staff->employee_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $staff->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $staff->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{ $staff->phone ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Designation</th>
                                            <td>{{ $staff->designation ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $staff->department ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Join Date</th>
                                            <td>{{ $staff->join_date ? $staff->join_date->format('d-m-Y') : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Salary</th>
                                            <td>{{ $staff->salary ? number_format($staff->salary, 2) : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Profile Image</th>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ $staff->profile_image ? url($staff->profile_image) : 'https://via.placeholder.com/150' }}"
                                                    class="text-decoration-none img-fluid rounded d-flex align-items-center">
                                                    <img src="{{ $staff->profile_image ? url($staff->profile_image) : 'https://via.placeholder.com/60' }}"
                                                        class="staff-profile-image-small"
                                                        alt="{{ $staff->name }}'s Profile Image"
                                                        style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>CV</th>
                                            <td>
                                                @if ($staff->cv)
                                                    <a class="btn btn-info btn-sm" href="{{ url($staff->cv) }}"
                                                        target="_blank">View
                                                        CV</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{ $staff->address ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if ($staff->status == '1')
                                                    <span class="badge badge-success">Running Employee</span>
                                                @else
                                                    <span class="badge badge-danger">Left the Employee</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $staff->created_at->format('d-m-Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td>{{ $staff->updated_at->format('d-m-Y H:i') }}</td>
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

@push('js')
    <script>
        // Initialize Select2 if necessary
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
