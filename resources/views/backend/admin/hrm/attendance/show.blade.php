@extends('layouts.admin', [$pageTitle => 'Leave Application Show'])

@section('admin')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
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
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Staff Name</th>
                                    <td>{{ isset($attendance->staff->full_name) ? ucwords(strtolower($attendance->staff->full_name)) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Shift Name</th>
                                    <td>{{ isset($attendance->shift->name) ? ucwords(strtolower($attendance->shift->name)) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->time)->format('h:i A') ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Latitude</th>
                                    <td>{{ $attendance->late ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Longitude</th>
                                    <td>{{ $attendance->long ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $attendance->location ?? 'N/A' }}</td>
                                </tr>
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
