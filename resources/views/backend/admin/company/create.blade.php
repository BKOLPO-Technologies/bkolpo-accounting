@extends('layouts.admin', ['pageTitle' => 'Company Create'])

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
                                    <a href="{{ route('company.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('company.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Company Name -->
                                        <div class="col-md-6 mb-2">
                                            <label for="name" class="form-label">Company Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Company Name">
                                            </div>
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Branch Selection -->
                                        <div class="col-md-6 mb-2">
                                            <label for="branch_id" class="form-label">Branch</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                                <select name="branch_id" id="branch_id" class="form-control select2">
                                                    <option value="">Select Branch</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button class="btn btn-danger" type="button" id="addBranchBtn" data-toggle="modal" data-target="#createBranchModal">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @error('branch_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" name="address" value="{{ old('address') }}" id="address" class="form-control" placeholder="Enter Company Address">
                                                </div>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                    <input type="text" name="city" value="{{ old('city') }}" id="city" class="form-control" placeholder="Enter City">
                                                </div>
                                                @error('city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                    <input type="text" name="country" value="{{ old('country') }}" id="country" class="form-control" placeholder="Enter Country">
                                                </div>
                                                @error('country')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input type="text" name="state" value="{{ old('state', $company->state ?? '') }}" id="state" class="form-control" placeholder="Enter State">
                                                </div>
                                                @error('state')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="post_code">Post Code</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                    <input type="text" name="post_code" value="{{ old('post_code') }}" id="post_code" class="form-control" placeholder="Enter Post Code">
                                                </div>
                                                @error('post_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="number" name="phone" value="{{ old('phone') }}" id="phone" class="form-control" placeholder="Enter Phone">
                                                </div>
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control" placeholder="Enter Email">
                                                </div>
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-6 mb-2">
                                            <label for="status" class="form-label">Status</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12 mb-2">
                                            <label for="description" class="form-label">Description</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
                                            </div>
                                            @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-11 mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                        </div>
                                        <div class="col-md-1 mb-3">
                                            <!-- Preview Container -->
                                            <div class="mt-3">
                                                <img
                                                    id="logoPreview"
                                                    src="{{ !empty($company->logo) ? url('upload/company/' . $company->logo) : url(asset('backend/logo.jpg')) }}" 
                                                    alt="Logo"
                                                    style="width: 100%; height: 60px; border: 1px solid #ddd; border-radius: 5px;">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="table-responsive mt-3">
                                                <table class="table table-bordered table-striped" id="dynamicTable">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th class="text-center">Type</th>
                                                            <th class="text-center">Group</th>
                                                            <th class="text-center">Sub Group</th>
                                                            <th class="text-center">Ledger</th>
                                                            <th class="text-center">O.B.</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {{-- First Row (Input Fields) --}}
                                                        <tr>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-folder"></i></span>
                                                                    <select class="form-control" name="type[]">
                                                                        <option value="">Select Type</option>
                                                                        <option value="Asset">Asset</option>
                                                                        <option value="Liability">Liability</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-folder"></i></span>
                                                                    <input type="text" class="form-control" name="group[]" placeholder="Enter Group">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-sitemap"></i></span>
                                                                    <input type="text" class="form-control" name="sub[]" placeholder="Enter Sub Group">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fa fa-book"></i></span>
                                                                    <input type="text" class="form-control" name="ledger[]" placeholder="Enter Ledger">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-text">BDT</span>
                                                                    <input type="number" class="form-control" name="ob[]" placeholder="Enter O.B.">
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-success addRow"><i class="fa fa-plus"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>                                                                      
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="row mt-3">
                                        <div class="col-lg-12 text-end">
                                            <button type="submit" class="btn btn-success" style="float: right;">
                                                <i class="fas fa-plus"></i> Add Company
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

    
<!-- Modal for creating a new branch -->
@include('backend.admin.branch.branch_modal')

@endsection
@push('js')
<script>
    // select 2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Branch",
            allowClear: true
        });
    });
    
    $(document).ready(function () {
        // Function to add a new row
        $(".addRow").click(function () {
            var newRow = `<tr>
                <td>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-folder"></i></span>
                        <select class="form-control" name="type[]">
                            <option value="">Select Type</option>
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-folder"></i></span>
                        <input type="text" class="form-control" name="group[]" placeholder="Enter Group">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-sitemap"></i></span>
                        <input type="text" class="form-control" name="sub[]" placeholder="Enter Sub Group">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-book"></i></span>
                        <input type="text" class="form-control" name="ledger[]" placeholder="Enter Ledger">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">BDT</span>
                        <input type="number" class="form-control" name="ob[]" placeholder="Enter O.B.">
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fa fa-minus"></i></button>
                </td>
            </tr>`;
            $("#dynamicTable tbody").append(newRow);
        });

        // Function to remove a row
        $(document).on("click", ".removeRow", function () {
            $(this).closest("tr").remove();
        });
    });

</script>
<script>
    // Company Logo
    document.getElementById('logo').addEventListener('change', function (event) {
        const file = event.target.files[0]; // Get the selected file
        const preview = document.getElementById('logoPreview'); // Get the preview element
        const defaultImage = 'https://via.placeholder.com/70x60'; // Online default image URL

        if (file) {
            const reader = new FileReader(); // Create a FileReader object

            reader.onload = function (e) {
                preview.src = e.target.result; // Set the image source to the uploaded file
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            preview.src = defaultImage; // Reset to the default online image
        }
    });
</script>


<script> 
    $('#createBranchForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = $(this).serialize(); // Get form data

        $.ajax({
            url: '{{ route('branch.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Check if the supplier was created successfully
                if (response.success) {
                    // Close the modal
                    $('#createBranchModal').modal('hide');
                    
                    // Clear form inputs
                    $('#createBranchForm')[0].reset();

                    // Append new supplier to the supplier select dropdown
                    $('#branch_id').append(new Option(response.branch.name, response.branch.id));

                    // Re-initialize the select2 to refresh the dropdown
                    $('#branch_id').trigger('change');

                    // Show success message
                    toastr.success('Branch added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                // Handle error (validation errors, etc.)
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_branch_${field}`).addClass('is-invalid');
                    $(`#new_branch_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>
@endpush
