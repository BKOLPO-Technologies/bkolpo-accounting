@extends('layouts.admin')
@section('admin')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>DataTables</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.supplier.index') }}" style="text-decoration: none; color: black;">Supplier</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">Add New supplier Details</h3>
                <!-- <br>
                <span style="font-size: 12px;">Put Branch Manage Information</span> -->
              </div>
              
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" name="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="form-control" placeholder="Company Name" name="company">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="number" class="form-control" placeholder="Phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Email Address" name="email">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control" placeholder="Address" name="address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" class="form-control" placeholder="City" name="city">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Region</label>
                                <input type="text" class="form-control" placeholder="Region" name="region">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control" placeholder="Country" name="country">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PostBox</label>
                                <input type="text" class="form-control" placeholder="Post Box" name="postbox">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>TAX ID</label>
                                <input type="number" class="form-control" placeholder="Tax Id" name="taxid">
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>


</div>
@endsection