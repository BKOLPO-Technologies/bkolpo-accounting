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
                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">DataTable with minimal features & hover style</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">

                        <!-- -------------- -->
                        <article class="content">
                            
                            <div id="notify" class="alert alert-success" style="display:none;">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>

                                <div class="message"></div>
                            </div>
                            <div class="grid_3 grid_4">
                                <div class="header-block">
                                    <h3 class="title">
                                        Projects <a href="https://accounts.bkolpo.com/projects/addproject" class="btn btn-primary btn-sm rounded">
                                            Add new </a>
                                    </h3>
                                </div>
                                <p>&nbsp;</p>
                                <div class="row">
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="pink" id="dash_0">0</h3>
                                                            <span>Waiting</span>
                                                        </div>
                                                        <style>
                                                            .pink {
                                                                color:rgb(255, 0, 0);
                                                            }

                                                            .font-large-2 {
                                                                font-size: 3rem; /* Adjust size as needed */
                                                            }

                                                            .float-xs-right {
                                                                float: right;
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <!-- <i class="icon-clock3 pink font-large-2 float-xs-right"></i> -->
                                                            <!-- <i class="fa-regular fa-clock"></i> -->
                                                            <i class="fa-regular fa-clock pink font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="indigo" id="dash_1">0</h3>
                                                            <span>In Progress</span>
                                                        </div>
                                                        <style>
                                                            .indigo {
                                                                color:rgb(0, 38, 255);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <!-- <i class="icon-spinner5 indigo font-large-2 float-xs-right"></i> -->
                                                            <i class="fa-solid fa-circle-notch indigo font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="green" id="dash_2">0</h3>
                                                            <span>Finished</span>
                                                        </div>
                                                        <style>
                                                            .green {
                                                                color:rgb(16, 187, 0);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <!-- <i class="icon-clipboard2 green font-large-2 float-xs-right"></i> -->
                                                            <i class="fa-regular fa-circle-check green font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="deep-cyan" id="dash_6">1</h3>
                                                            <span>Total</span>
                                                        </div>
                                                        <style>
                                                            .deep-cyan {
                                                                color:rgb(59, 59, 59);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <!-- <i class="icon-stats-bars22 deep-cyan font-large-2 float-xs-right"></i> -->
                                                            <i class="fa-solid fa-signal deep-cyan font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            
                            <input type="hidden" id="dashurl" value="projects/projects_stats">
                        </article>
                        <!-- -------------- -->

                        <br>

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Settings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>Ecommerce</td>
                                    <td>19-01-2025</td>
                                    <td>Bkolpo Construction</td>
                                    <td>Due</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.invoiceDetails') }}'">
                                            <i class="fa-regular fa-file-lines" style="margin-right: 8px;"></i>View
                                        </button>

                                        <!-- <a type="button" class="btn btn-primary" href="{{ route('admin.invoiceDetails') }}">
                                            <i class="fa-regular fa-file-lines" style="margin-right: 8px;"></i>View
                                        </a> -->

                                        <button type="submit" class="btn btn-info"><i class="fa-solid fa-download"></i></button>
                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                            <!-- <tfoot>
                                <tr>
                                    <th>Rendering engine</th>
                                    <th>Browser</th>
                                    <th>Platform(s)</th>
                                    <th>Engine version</th>
                                    <th>CSS grade</th>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->


</div>
<!-- /.content-wrapper -->

@endsection