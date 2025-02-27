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
                <div class="card card-secondary">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title">All Suppliers</h3>
                        <a href="{{ route('admin.supplier.create') }}" class="btn btn-success ml-auto">Add Supplier</a>
                    </div>

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
                                @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->address }}</td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>{{ $supplier->phone }}</td>
                                    <td>
                                        <a href="{{ route('admin.supplier.view', $supplier->id) }}" class="btn btn-info">
                                            View <i class="fa-regular fa-eye ml-3"></i>
                                        </a>
                                        <a href="{{ route('admin.supplier.edit', $supplier->id) }}" class="btn btn-primary">
                                            Edit <i class="fa-solid fa-pen ml-3"></i>
                                        </a>

                                        <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div> 
        </div>

    </section>

</div>

@endsection
