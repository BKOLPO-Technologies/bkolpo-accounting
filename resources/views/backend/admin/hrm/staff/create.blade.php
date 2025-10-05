@extends('layouts.admin', [$pageTitle => 'Create Staff'])
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
                                <form method="POST" action="{{ route('admin.staff.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="employee_id">Employee ID <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="employee_id" id="employee_id"
                                                    class="form-control @error('employee_id') is-invalid @enderror"
                                                    value="{{ old('employee_id') }}" required
                                                    placeholder="Enter Employee ID">
                                                @error('employee_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="join_date">Join Date <span class="text-danger">*</span></label>
                                                <input type="date" name="join_date" id="join_date"
                                                    class="form-control @error('join_date') is-invalid @enderror"
                                                    value="{{ old('join_date') }}" required placeholder="Enter Join Date">
                                                @error('join_date')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" required placeholder="Enter Name">
                                                @error('name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}" required placeholder="Enter Email">
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" name="phone" id="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone') }}" placeholder="Enter Phone" max="11">
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="department">Department <span
                                                        class="text-danger">*</span></label>
                                                <select name="department" id="department"
                                                    class="form-control @error('department') is-invalid @enderror" required>
                                                    <option value="">Select Department</option>
                                                    <option value="IT"
                                                        {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                                    <option value="HR"
                                                        {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                                                    <option value="Finance"
                                                        {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance
                                                    </option>
                                                    <option value="Marketing"
                                                        {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing
                                                    </option>
                                                    <option value="Sales"
                                                        {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                                </select>

                                                @error('department')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="designation">Designation <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="designation" id="designation"
                                                    class="form-control @error('designation') is-invalid @enderror"
                                                    value="{{ old('designation') }}" required
                                                    placeholder="Enter Designation">
                                                @error('designation')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="salary">Basic Salary <span class="text-danger">*</span></label>
                                                <input type="number" name="salary" id="salary" step="0.01"
                                                    class="form-control @error('salary') is-invalid @enderror"
                                                    value="{{ old('salary') }}" required placeholder="Enter Basic Salary">
                                                @error('salary')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --}}
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
                                                            src="{{ asset('backend/assets/img/user2-160x160.jpg ') }}"
                                                            alt="Profile Preview"
                                                            style="width: 70px; height: 60px; border: 1px solid #ddd; border-radius: 5px; object-fit: cover;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-10 mb-3">
                                                    <label for="cv" class="form-label">CV (PDF Format) <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file" class="form-control" id="cv"
                                                        name="cv" accept="application/pdf">
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
                                    </div>
                                    <h5 class="mt-2 text-success font-weight-bolder">Salary Structure</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="basic">Basic Salary</label>
                                            <input type="number" min="0" name="basic" id="basic" class="form-control"
                                                placeholder="Enter Basic Salary" value="{{ old('salary') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="hra">HRA</label>
                                            <input type="number" min="0" name="hra" id="hra" class="form-control"
                                                placeholder="Enter House Rent" value="{{ old('hra') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="medical">Medical</label>
                                            <input type="number" min="0" name="medical" id="medical" class="form-control"
                                                placeholder="Enter Medical" value="{{ old('medical') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="conveyance">Conveyance</label>
                                            <input type="number" min="0" name="conveyance" id="conveyance" class="form-control"
                                                placeholder="Enter Conveyance" value="{{ old('conveyance') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label for="pf">PF</label>
                                            <input type="number" min="0" name="pf" id="pf" class="form-control"
                                                placeholder="Enter Provident Found" value="{{ old('pf') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tax">Tax</label>
                                            <input type="number" min="0" name="tax" id="tax" class="form-control"
                                                placeholder="Enter Tax" value="{{ old('tax') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="other_deduction">Other Deduction</label>
                                            <input type="number" min="0" name="other_deduction" id="other_deduction"
                                                class="form-control" placeholder="Enter Other Deducation"
                                                value="{{ old('other_deduction') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="other_deduction">Gross Salary</label>
                                            <input type="number" min="0" name="gross_salary" id="gross_salary"
                                                class="form-control" placeholder="Enter Gross Salary"
                                                value="{{ old('gross_salary') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                                    placeholder="Enter Address">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light"
                                                style="float: right;"><i class="fas fa-plus"></i> Add Staff</button>
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
        // Staff Profile Photo Show
        document.getElementById('profile_image').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file
            const preview = document.getElementById('profileImagePreview'); // Get the preview element
            const defaultImage = 'https://via.placeholder.com/70x60'; // Online default image URL

            if (file) {
                const reader = new FileReader(); // Create a FileReader object

                reader.onload = function(e) {
                    preview.src = e.target.result; // Set the image source to the uploaded file
                };

                reader.readAsDataURL(file); // Read the file as a data URL
            } else {
                preview.src = defaultImage; // Reset to the default online image
            }
        });


        // Select 2 
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();
        });
    </script>
    <script>
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
