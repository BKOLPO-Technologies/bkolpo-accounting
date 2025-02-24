@extends('layouts.admin', ['pageTitle' => 'Client Edit'])

@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <!-- <h3 class="card-title">Edit client Details</h3> -->
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                    <a href="{{ route('admin.client.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
              
              <!-- /.card-header -->
              <!-- form start -->
              <!-- <form role="form"> -->
              <form action="{{ route('admin.client.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" name="name" value="{{ $client->name }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" placeholder="Company Name" name="company" value="{{ $client->company }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="number" class="form-control" placeholder="Phone" name="phone" value="{{ $client->phone }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ $client->email }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="Address" name="address" value="{{ $client->address }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" placeholder="City" name="city" value="{{ $client->city }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Region</label>
                                <input type="text" class="form-control" placeholder="Region" name="region" value="{{ $client->region }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control" placeholder="Country" name="country" value="{{ $client->country }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PostBox</label>
                                <input type="text" class="form-control" placeholder="Post Box" name="postbox" value="{{ $client->postbox }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>TAX ID</label>
                                <input type="number" class="form-control" placeholder="Tax Id" name="taxid" value="{{ $client->taxid }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-paper-plane"></i> Update Client</button>
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