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
                                            </div>
                                            @error('branch_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-12 mb-2">
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

                                        {{-- Table --}}
                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-secondary">
                                                    <tr>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Group</th>
                                                        <th class="text-center">Sub Group</th>
                                                        <th class="text-center">Ledger</th>
                                                        <th class="text-center">O.B.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- First Row (Input Fields) --}}
                                                    <tr>
                                                        <td>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa fa-folder"></i></span>
                                                                <select class="form-control" name="group[]">
                                                                    <option value="">Select Group</option>
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
                                                                <span class="input-group-text"><i class="fa fa-dollar-sign"></i></span>
                                                                <input type="number" class="form-control" name="ob[]" placeholder="Enter O.B.">
                                                            </div>
                                                        </td>
                                                    </tr>
                                        
                                                    {{-- Second Row (Submit Buttons for Each Column) --}}
                                                    {{-- <tr>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-save"></i> Save Group
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-save"></i> Save Sub
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-save"></i> Save Ledger
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-save"></i> Save O.B.
                                                            </button>
                                                        </td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
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

</script>
@endpush
