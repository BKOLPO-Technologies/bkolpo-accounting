@extends('layouts.admin', ['pageTitle' => 'Company Edit'])

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
                                <form method="POST" action="{{ route('company.update',$company->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Company Name -->
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Company Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="name" 
                                                    name="name" 
                                                    value="{{ old('name', $company->name) }}" 
                                                    placeholder="Enter Company Name">
                                            </div>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Branch Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label for="branch_id" class="form-label">Branch</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                                                <select name="branch_id" id="branch_id" class="form-control select2">
                                                    <option value="">Select Branch</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ old('branch_id', $company->branch_id) == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('branch_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Address Selection -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text" name="address" value="{{ old('address', $company->address) }}" id="address" class="form-control" placeholder="Enter Address">
                                                </div>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- City Selection -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                    <input type="text" name="city" value="{{ old('city', $company->city) }}" id="city" class="form-control" placeholder="Enter City">
                                                </div>
                                                @error('city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Country Selection -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                    <input type="text" name="country" value="{{ old('country', $company->country) }}" id="country" class="form-control" placeholder="Enter Country">
                                                </div>
                                                @error('country')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- State Selection -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="state">State</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                    <input type="text" name="state" value="{{ old('state', $company->state) }}" id="state" class="form-control" placeholder="Enter State">
                                                </div>
                                                @error('state')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Post Code -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="post_code">Post Code</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                                    <input type="text" name="post_code" value="{{ old('post_code', $company->post_code) }}" id="post_code" class="form-control" placeholder="Enter Post Code">
                                                </div>
                                                @error('post_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="number" name="phone" value="{{ old('phone', $company->phone) }}" id="phone" class="form-control" placeholder="Enter Phone">
                                                </div>
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    <input type="email" name="email" value="{{ old('email', $company->email) }}" id="email" class="form-control" placeholder="Enter Email">
                                                </div>
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-lg-6">
                                            <label for="status" class="form-label">Status</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" {{ old('status', $company->status) == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status', $company->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Currency Symbol -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="currency_symbol">Currency Symbol</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $company->currency_symbol) }}" id="currency_symbol" class="form-control" placeholder="Enter Currency Symbol">
                                                </div>
                                                @error('currency_symbol')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Fiscal Year -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fiscal_year">Fiscal Year</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text" name="fiscal_year" value="{{ old('fiscal_year', $company->fiscal_year) }}" id="fiscal_year" class="form-control" placeholder="Enter Fiscal Year">
                                                </div>
                                                @error('fiscal_year')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- VAT -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="vat">VAT</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="number" min="0" name="vat" value="{{ old('vat', $company->vat) }}" id="vat" class="form-control" placeholder="Enter VAT">
                                                </div>
                                                @error('vat')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- TAX -->
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="tax">TAX</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="number" min="0" name="tax" value="{{ old('tax', $company->tax) }}" id="tax" class="form-control" placeholder="Enter TAX">
                                                </div>
                                                @error('tax')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12 mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                                <textarea 
                                                    class="form-control" 
                                                    id="description" 
                                                    name="description" 
                                                    rows="3" 
                                                    placeholder="Enter description">{{ old('description', $company->description) }}</textarea>
                                            </div>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Company Logo -->
                                        <div class="col-md-11 mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                        </div>

                                        <!-- Preview Container -->
                                        <div class="col-md-1 mb-3">
                                            <div class="mt-3">
                                                <img
                                                    id="logoPreview"
                                                    src="{{ !empty($company->logo) ? url('upload/company/' . $company->logo) : url(asset('backend/logo.jpg')) }}" 
                                                    alt="Logo"
                                                    style="width: 100%; height: 60px; border: 1px solid #ddd; border-radius: 5px;">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Submit Button -->
                                    <div class="row mt-3">
                                        <div class="col-lg-12 text-end">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-paper-plane"></i> Update Company
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
    // select 2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select roles",
            allowClear: true
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
@endpush
