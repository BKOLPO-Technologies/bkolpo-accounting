@extends('layouts.admin', ['pageTitle' => 'Journal Voucher Create'])

@section('admin')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <!-- <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1> -->
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div>
              </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('journal-voucher.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Company Select -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="company_id">Account Type</label>
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                                            <option value="">Select Account Type</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Select Detail type -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="company_id">Detail Type</label>
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                                            <option value="">Select Detail Type</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Name -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="company_id">Name</label>
                                        <input class="form-control" type="text" name="" id="">
                                    </div>

                                    <!-- Select Parent account -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="company_id">Parent account</label>
                                        <select name="company_id" id="company_id" class="form-control select2 @error('company_id') is-invalid @enderror">
                                            <option value="">Select Parent account</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <!-- Balance -->
                                        <div class="col-lg-6 mb-3">
                                            <label for="company_id">Balance</label>
                                            <input class="form-control" type="text" name="" id="">
                                        </div>

                                        <!-- As of -->
                                        <div class="col-lg-6 mb-3">
                                            <label for="company_id">As of</label>
                                            <input type="datetime-local" class="form-control" name="" id="">
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-lg-12 mb-3">
                                        <label for="company_id">Description</label>
                                        <textarea class="summernote"
                                                    placeholder=" Note"
                                                    autocomplete="false" rows="10" name="content"></textarea>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Save Journal Voucher
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

@endpush

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script type="text/javascript">

    $(function () {
        $('.select-box').select2();

        $('.summernote').summernote({
            height: 250,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });

</script>
