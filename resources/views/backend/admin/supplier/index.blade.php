@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Supplier</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Settings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Agrotech</td>
                                    <td>Kaduna</td>
                                    <td>Kaduna</td>
                                    <td>Kaduna</td>
                                    <td>
                                        <a href="{{ route('admin.supplier.view', ['id' => 1]) }}" class="btn btn-info">
                                            View <i class="fa-regular fa-eye ml-3"></i>
                                        </a>
                                        <a href="{{ route('admin.supplier.edit', ['id' => 1]) }}" class="btn btn-primary">
                                            Edit <i class="fa-solid fa-pen ml-3"></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                            
                        </table>
                    </div>
                    
                </div>
            </div> 
        </div>

    </section>

</div>

@endsection