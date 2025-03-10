@extends('layouts.admin', [$pageTitle => 'Company Information'])
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
                        <li class="breadcrumb-item active"> Settings / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <!-- Conversations Title -->
                                <h3 class="card-title">Company Information</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('company-information.update', $company->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="company_name">Company Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" name="company_name" value="{{ old('company_name', $company->company_name ?? '') }}" id="company_name" class="form-control" placeholder="Enter Company Name">
                                                </div>
                                                @error('company_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" name="address" value="{{ old('address', $company->address ?? '') }}" id="address" class="form-control" placeholder="Enter Company Address">
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
                                                    <input type="text" name="city" value="{{ old('city', $company->city ?? '') }}" id="city" class="form-control" placeholder="Enter City">
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
                                                    <input type="text" name="country" value="{{ old('country', $company->country ?? '') }}" id="country" class="form-control" placeholder="Enter Country">
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
                                                    <input type="text" name="post_code" value="{{ old('post_code', $company->post_code ?? '') }}" id="post_code" class="form-control" placeholder="Enter Post Code">
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
                                                    <input type="number" name="phone" value="{{ old('phone', $company->phone ?? '') }}" id="phone" class="form-control" placeholder="Enter Phone">
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
                                                    <input type="email" name="email" value="{{ old('email', $company->email ?? '') }}" id="email" class="form-control" placeholder="Enter Email">
                                                </div>
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-8 mb-3">
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
                                        <div class="col-md-3 mb-3">
                                            <div class="form-group">
                                                <label for="company_branch">Company Branch</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" name="company_branch" value="{{ old('company_branch', $company->company_branch ?? '') }}" id="company_branch" class="form-control" placeholder="Enter Company Branch">
                                                </div>
                                                @error('company_branch')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-plus"></i> Update Company Information
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
@endpush
