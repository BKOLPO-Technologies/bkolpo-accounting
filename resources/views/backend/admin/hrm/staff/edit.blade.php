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
                                        <div class="col-md-4">
                                            <div class="row align-items-end">
                                                <div class="col-md-10 mb-3">
                                                    <label for="cv" class="form-label">CV (PDF Format) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" id="cv"
                                                        name="cv" accept="application/pdf">
                                                </div>

                                                <div class="col-md-2 mb-3 text-end">
                                                    <!-- Old CV Button -->
                                                    @if ($staff->cv)
                                                        <a id="oldCvBtn" href="{{ url($staff->cv) }}" target="_blank"
                                                            class="btn btn-sm btn-success mb-1 w-100">View</a>
                                                    @endif

                                                    <!-- New CV Preview Button (Initially Hidden) -->
                                                    <a id="cvPreviewBtn" href="#" target="_blank"
                                                        class="btn btn-sm btn-info d-none w-100">Preview</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <h5 class="mt-2 text-success font-weight-bolder">Salary Structure</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="basic">Basic Salary</label>
                                            <input type="number" min="0" name="basic" id="basic" class="form-control"
                                                placeholder="Enter Basic Salary" value="{{ old('salary', $staff->salaryStructure->basic ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="hra">HRA</label>
                                            <input type="number" min="0" name="hra" id="hra" class="form-control"
                                                placeholder="Enter House Rent" value="{{ old('hra', $staff->salaryStructure->hra ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="medical">Medical</label>
                                            <input type="number" name="medical" id="medical" class="form-control"
                                                placeholder="Enter Medical" value="{{ old('medical', $staff->salaryStructure->medical ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="conveyance">Conveyance</label>
                                            <input type="number" min="0" name="conveyance" id="conveyance" class="form-control"
                                                placeholder="Enter Conveyance" value="{{ old('medical', $staff->salaryStructure->conveyance ?? 0) }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="pf">PF</label>
                                            <input type="number" name="pf" id="pf" class="form-control"
                                                placeholder="Enter Provident Found" value="{{ old('medical', $staff->salaryStructure->pf ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tax">Tax</label>
                                            <input type="number" min="0" name="tax" id="tax" class="form-control"
                                                placeholder="Enter Tax" value="{{ old('medical', $staff->salaryStructure->tax ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="other_deduction">Other Deduction</label>
                                            <input type="number" min="0" name="other_deduction" id="other_deduction"
                                                class="form-control" placeholder="Enter Other Deducation"
                                                value="{{ old('medical', $staff->salaryStructure->other_deduction ?? 0) }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="other_deduction">Gross Salary</label>
                                            <input type="number" min="0" name="gross_salary" id="gross_salary"
                                                class="form-control" placeholder="Enter Gross Salary"
                                                value="{{ old('medical', $staff->salaryStructure->gross ?? 0) }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="status">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1"
                                                    {{ old('status', $staff->status ?? 1) == 1 ? 'selected' : '' }}>Running
                                                    Employee
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
            let previewBtn = document.getElementById('cvPreviewBtn');
            let oldBtn = document.getElementById('oldCvBtn');

            if (file && file.type === "application/pdf") {
                let fileURL = URL.createObjectURL(file);

                // Show preview of new CV
                previewBtn.href = fileURL;
                previewBtn.classList.remove("d-none");

                // Hide old CV button
                if (oldBtn) {
                    oldBtn.classList.add("d-none");
                }
            } else {
                // Invalid file, hide preview
                previewBtn.classList.add("d-none");

                // Optionally show old CV again if user clears selection
                if (oldBtn) {
                    oldBtn.classList.remove("d-none");
                }

                alert("Please upload a valid PDF file.");
            }
        });
    </script>
      <script>
    document.addEventListener('DOMContentLoaded', function () {
        const basic = document.getElementById('basic');
        const hra = document.getElementById('hra');
        const medical = document.getElementById('medical');
        const conveyance = document.getElementById('conveyance');
        const pf = document.getElementById('pf');
        const tax = document.getElementById('tax');
        const other_deduction = document.getElementById('other_deduction');
        const gross_salary = document.getElementById('gross_salary');

        function calculateGrossSalary() {
            // Convert all input values to float safely
            const basicVal = parseFloat(basic.value) || 0;
            const hraVal = parseFloat(hra.value) || 0;
            const medicalVal = parseFloat(medical.value) || 0;
            const conveyanceVal = parseFloat(conveyance.value) || 0;
            const pfVal = parseFloat(pf.value) || 0;
            const taxVal = parseFloat(tax.value) || 0;
            const otherDeductionVal = parseFloat(other_deduction.value) || 0;

            // Formula: Total Earnings - Total Deductions
            const totalEarnings = basicVal + hraVal + medicalVal + conveyanceVal;
            const totalDeductions = pfVal + taxVal + otherDeductionVal;
            const gross = totalEarnings - totalDeductions;

            gross_salary.value = gross.toFixed(2);
        }

        // Attach keyup listeners
        [basic, hra, medical, conveyance, pf, tax, other_deduction].forEach(input => {
            input.addEventListener('keyup', calculateGrossSalary);
            input.addEventListener('change', calculateGrossSalary);
        });
    });
</script>
@endpush
