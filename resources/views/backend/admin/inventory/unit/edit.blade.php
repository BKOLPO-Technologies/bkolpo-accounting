@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Unit Edit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.unit.index') }}" style="text-decoration: none; color: black;">Unit</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                    <a href="{{ route('admin.unit.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
              
              <!-- /.card-header -->
              <!-- form start -->
              <!-- <form role="form"> -->
              <form action="{{ route('admin.unit.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name
                                  @error('name')
                                      <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Cateogry Name" name="name" value="{{ $unit->name }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="status" class="form-label">Status
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $unit->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $unit->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row mt-3">
                          <div class="col-lg-12">
                              <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-paper-plane"></i> Update Unit</button>
                          </div>
                      </div> 
                </div>
                <!-- /.card-body -->
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>


</div>
@endsection