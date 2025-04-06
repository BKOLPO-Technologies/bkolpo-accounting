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

                                        <!-- Status -->
                                        <div class="col-md-12 mb-3">
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
@endpush
