@extends('layouts.admin', [$pageTitle => 'Edit Staff'])

@section('admin')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
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
                                <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                                            <input type="text" name="employee_id" id="employee_id"
                                                class="form-control @error('employee_id') is-invalid @enderror"
                                                value="{{ old('employee_id', $staff->employee_id) }}" required>
                                            @error('employee_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="join_date">Join Date <span class="text-danger">*</span></label>
                                            <input type="date" name="join_date" id="join_date"
                                                class="form-control @error('join_date') is-invalid @enderror"
                                                value="{{ old('join_date', $staff->join_date->format('Y-m-d')) }}" required>
                                            @error('join_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $staff->name) }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $staff->email) }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', $staff->phone) }}">
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="department">Department <span class="text-danger">*</span></label>
                                            <select name="department" id="department"
                                                class="form-control @error('department') is-invalid @enderror" required>
                                                <option value="">Select Department</option>
                                                @foreach (['IT', 'HR', 'Finance', 'Marketing', 'Sales'] as $dept)
                                                    <option value="{{ $dept }}"
                                                        {{ old('department', $staff->department) == $dept ? 'selected' : '' }}>
                                                        {{ $dept }}</option>
                                                @endforeach
                                            </select>
                                            @error('department')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="designation">Designation <span class="text-danger">*</span></label>
                                            <input type="text" name="designation" id="designation"
                                                class="form-control @error('designation') is-invalid @enderror"
                                                value="{{ old('designation', $staff->designation) }}" required>
                                            @error('designation')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="salary">Salary <span class="text-danger">*</span></label>
                                            <input type="number" name="salary" id="salary" step="0.01"
                                                class="form-control @error('salary') is-invalid @enderror"
                                                value="{{ old('salary', $staff->salary) }}" required>
                                            @error('salary')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-10 mb-3">
                                                    <label for="profile_image" class="form-label">Profile Image</label>
                                                    <input type="file" class="form-control" id="profile_image"
                                                        name="profile_image" accept="image/*">
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <!-- Preview Container -->
                                                    <div class="mt-3">
                                                        <img id="profileImagePreview"
                                                            src="{{ $staff->profile_image ? url($staff->profile_image) : 'https://via.placeholder.com/70x60' }}"
                                                            alt="Profile Preview"
                                                            style="width: 70px; height: 60px; border: 1px solid #ddd; border-radius: 5px; object-fit: cover;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-10 mb-3">
                                                    <label for="cv" class="form-label">CV (PDF Format) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" id="cv"
                                                        name="cv" accept="application/pdf">
                                                    @if ($staff->cv)
                                                        <a href="{{ url($staff->cv) }}" target="_blank"
                                                            class="btn btn-sm btn-success mt-1">View CV</a>
                                                    @endif
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <!-- Button preview -->
                                                    <div class="mt-4">
                                                        <a id="cvPreviewBtn" href="#" target="_blank"
                                                            class="btn btn-sm btn-info d-none">View CV</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1"
                                                    {{ old('status', $staff->status ?? 1) == 1 ? 'selected' : '' }}>Running Employee
                                                </option>
                                                <option value="0"
                                                    {{ old('status', $staff->status ?? 1) == 0 ? 'selected' : '' }}>
                                                    Left the Employee</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" rows="3" class="form-control">{{ old('address', $staff->address) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>
                                                Update Staff</button>
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
        // Profile Image Preview
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('profileImagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // CV Preview
        document.getElementById('cv').addEventListener('change', function(event) {
            let file = event.target.files[0];
            let btn = document.getElementById('cvPreviewBtn');

            if (file && file.type === "application/pdf") {
                let fileURL = URL.createObjectURL(file);
                btn.href = fileURL;
                btn.classList.remove("d-none"); // Show button
            } else {
                btn.classList.add("d-none"); // Hide button if not PDF
                alert("Please upload a valid PDF file.");
            }
        });
    </script>
@endpush
