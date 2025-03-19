@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Unit List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Unit</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header">
                        <h3 class="card-title">All Units</h3>
                        <a href="{{ route('admin.unit.create') }}" class="btn btn-success float-right">
                            <i class="fas fa-plus fa-sm"></i> Add Unit
                        </a>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                <tr>
                                    <td class="col-1">{{ $loop->iteration }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>
                                        @if($unit->status == 1)
                                            <a href="#" class="badge badge-success">
                                                <span class="badge bg-success">Active</span>
                                            </a>
                                        @else
                                            <a href="#" class="badge badge-danger">
                                                <span class="badge bg-danger">Inactive</span>
                                            </a>
                                        @endif
                                    </td>     
                                    <td>
                                        <a href="{{ route('admin.unit.edit', $unit->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.unit.destroy', $unit->id) }}" id="delete" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
