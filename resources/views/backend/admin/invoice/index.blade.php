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
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Settings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>1001</td>
                                    <td>BKOLPO CONSTRUCTION LIMITED</td>
                                    <td>19-01-2025</td>
                                    <td>৳ 193,800.00</td>
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