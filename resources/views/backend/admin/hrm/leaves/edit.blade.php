@extends('layouts.admin', [$pageTitle => 'Edit Leave Application'])

@section('admin')
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
                <li class="breadcrumb-item active" aria-current="page">HR Management / {{ $pageTitle ?? 'N/A' }}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg mb-4">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('leaves.update', $leaveApplication->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="staff_id">Select Staff</label>
                                            <select name="staff_id" id="staff_id" class="form-control select2" required>
                                                <option value="">Select Staff</option>
                                                @foreach($staffs as $staff)
                                                    <option value="{{ $staff->id }}" {{ $leaveApplication->staff_id == $staff->id ? 'selected' : '' }}>
                                                        {{ $staff->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('staff_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="leave_type">Leave Type</label>
                                            <select name="leave_type" id="leave_type" class="form-control select2" required>
                                                <option value="">Select Leave Type</option>
                                                @foreach($leaveTypes as $leaveType)
                                                    <option value="{{ $leaveType->id }}" {{ $leaveApplication->leave_type_id == $leaveType->id ? 'selected' : '' }}>
                                                        {{ $leaveType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('leave_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $leaveApplication->start_date }}" required>
                                            @error('start_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $leaveApplication->end_date }}" required>
                                            @error('end_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="start_time">Start Time</label>
                                            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $leaveApplication->start_time }}">
                                            @error('start_time')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="end_time">End Time</label>
                                            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $leaveApplication->end_time }}">
                                            @error('end_time')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-10">
                                        <div class="form-group">
                                            <label for="documents">Documents Upload (Optional)</label>
                                            <input type="file" name="documents[]" class="form-control" placeholder="Choose Files" multiple>
                                            @error('documents')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-2 mt-1">
                                        <div class="form-group mt-4">
                                            @if ($leaveApplication && $leaveApplication->documents && $leaveApplication->documents->isNotEmpty())
                                                <!-- Display "View Document" button if documents exist -->
                                                @foreach ($leaveApplication->documents as $document)
                                                    <a href="{{ Storage::url($document->attacement) }}" class="btn btn-info" target="_blank">View Document</a>
                                                @endforeach
                                            @else
                                                <!-- Display message if no documents exist -->
                                                <p>No documents found.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Check if documents are uploaded for the leave application -->                              
                            
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-3">
                                            <label for="reason">Reason</label>
                                            <textarea name="reason" id="reason" rows="4" class="form-control" placeholder="Enter Reason" required>{{ $leaveApplication->reason }}</textarea>
                                            @error('reason')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                            <i class="fas fa-save"></i> Update Leave Application
                                        </button>
                                    </div>
                                </div>
                            </form>
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
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
