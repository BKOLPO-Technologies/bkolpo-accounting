@extends('layouts.admin', ['pageTitle' => 'Supplier Edit'])

@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>DataTables</h1> -->
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                        <a href="{{ route('admin.supplier.index')}}" class="btn btn-sm btn-danger rounded-0">
                            <i class="fa-solid fa-arrow-left"></i> Back To List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supplier.update', $supplier->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="name" value="{{ $supplier->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <input type="text" class="form-control" placeholder="Company Name" name="company" value="{{ $supplier->company }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input type="text" class="form-control" name="title" value="{{ $supplier->title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Designation</label>
                                        <input type="text" class="form-control" name="designation" value="{{ $supplier->designation }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ $supplier->phone }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ $supplier->email }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $supplier->address }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip no</label>
                                        <input type="text" class="form-control" name="zip" value="{{ $supplier->zip }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city" value="{{ $supplier->city }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control" placeholder="Country" name="country" value="{{ $supplier->country }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>BIN Number</label>
                                        <input type="text" class="form-control" name="bin" value="{{ $supplier->bin }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>TAX ID</label>
                                        <input type="text" class="form-control" placeholder="Tax Id" name="taxid" value="{{ $supplier->taxid }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                    <i class="fas fa-paper-plane"></i> Update Supplier
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