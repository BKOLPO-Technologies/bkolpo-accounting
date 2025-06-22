@extends('layouts.admin', ['pageTitle' => 'Branch Create'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <!-- <h1 class="m-0">{{ $pageTitle ?? ''}}</h1> -->
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
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
                                    <h4 class="mb-0">Employee Details</h4>
                                    <a href="{{ route('admin.employee.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                              <form method="POST" action="{{ route('admin.employee.store') }}" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row">

                                      <div class="col-md-12 mb-2">
                                          <label for="name" class="form-label">UserName (Use Only a-z0-9)
                                              @error('name')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="name" name="username" value="{{ old('name') }}" placeholder="Enter Branch Name">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="email" class="form-label">Email
                                              @error('email')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                              <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="password" class="form-label">Password
                                              @error('password')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                              <input type="text" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Password">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="role" class="form-label">Role
                                              @error('role')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                              <select class="form-control" id="role" name="role">
                                                @foreach($roles as $role)
                                                <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                                  
                                              </select>
                                          </div>
                                      </div>

                                      <hr>

                                      <div class="col-md-12 mb-2">
                                          <label for="name" class="form-label">Name
                                              @error('name')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="address" class="form-label">Address
                                              @error('address')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Address">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="city" class="form-label">City
                                              @error('city')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="City">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="region" class="form-label">Region
                                              @error('region')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="region" name="region" value="{{ old('region') }}" placeholder="Region">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="country" class="form-label">Country
                                              @error('country') 
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}" placeholder="Country">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="postbox" class="form-label">Postbox
                                              @error('postbox') 
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="postbox" name="postbox" value="{{ old('postbox') }}" placeholder="Postbox">
                                          </div>
                                      </div>

                                      <div class="col-md-12 mb-2">
                                          <label for="phone" class="form-label">Phone
                                              @error('phone') 
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-user"></i></span>
                                              <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Phone">
                                          </div>
                                      </div>
                                      
                                  </div>
                                  <div class="row mt-3">
                                      <div class="col-lg-12">
                                          <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-plus"></i> Add</button>
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
