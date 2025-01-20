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
                        <li class="breadcrumb-item"><a href="{{ route('admin.ledgername') }}" style="text-decoration: none; color: black;">Ledger Name Manage</a></li>
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
                            <h3 class="card-title">Create Ledger Name Manage</h3>
                            <!-- 
                            <br>
                            <span style="font-size: 12px;">Put Branch Manage Information</span> 
                            -->
                        </div>

                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Name" name="name">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label>Select Ledger Type</label>

                                            <select class="form-control">
                                                <option>option 1</option>
                                                <option>option 2</option>
                                                <option>option 3</option>
                                                <option>option 4</option>
                                                <option>option 5</option>
                                            </select>

                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label>Select Ledger Group</label>

                                            <select class="form-control">
                                                <option>option 1</option>
                                                <option>option 2</option>
                                                <option>option 3</option>
                                                <option>option 4</option>
                                                <option>option 5</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unit</label>
                                            <input type="text" class="form-control" placeholder="Unit" name="unit">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="radio1">
                                                <label class="form-check-label">Radio</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="radio1">
                                                <label class="form-check-label">Radio1</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row" style="margin-top: 30px;">
                                    <div class="col-md-12">
                                        <div class="form-group d-flex align-items-center">
                                            <label style="margin-right: 18px;">Type</label>
                                            <div class="form-check" style="margin-right: 10px;">
                                                <input class="form-check-input" type="radio" name="radio1" id="radio1">
                                                <label class="form-check-label" for="radio1">Dr</label>
                                            </div>
                                            <div class="form-check" style="margin-right: 10px;">
                                                <input class="form-check-input" type="radio" name="radio1" id="radio2">
                                                <label class="form-check-label" for="radio2">Cr</label>
                                            </div>
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
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->

@endsection