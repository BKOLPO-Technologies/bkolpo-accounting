@extends('layouts.admin', [$pageTitle => 'Role List'])
@section('admin')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"> Roles / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary shadow">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    @can('role-create')
                                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Role
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Permission</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $key => $role)
                                            @php
                                                $rolePermissions = Spatie\Permission\Models\Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                                                    ->where("role_has_permissions.role_id",$role->id)
                                                    ->get();
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @if(!empty($rolePermissions) && $rolePermissions->count())
                                                        <div style="max-width: 700px; overflow-x: auto; white-space: nowrap;">
                                                            @foreach($rolePermissions as $v)
                                                                <span class="badge badge-success">{{ $v->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p>No permissions assigned to this role.</p>
                                                    @endif
                                                </td>
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('roles.show',$role->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm"
                                                        @if($role->name == 'Admin') 
                                                            style="pointer-events: none; opacity: 0.5;" 
                                                            title="Admin role cannot be edited"
                                                        @endif>
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @can('role-delete')
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('roles.delete', $role->id) }}" id="delete" class="btn btn-danger btn-sm" 
                                                        @if($role->name == 'Admin') 
                                                            style="pointer-events: none; opacity: 0.5;" 
                                                            title="Cannot delete Admin role"
                                                        @endif>
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('js')

@endpush
