@extends('layouts.admin', ['pageTitle' => 'User List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
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
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    @can('user-create')
                                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New User
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
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $v)
                                                        <label class="badge bg-success">{{ $v }}</label>
                                                        @endforeach
                                                    @endif
                                                </td>   
                                                <td>
                                                    @if($user->status == 1)
                                                        <a href="#" class="badge badge-success">
                                                            <span class="badge bg-success">Active</span>
                                                        </a>
                                                    @else
                                                        <a href="#" class="badge badge-danger">
                                                            <span class="badge bg-danger">Inactive</span>
                                                        </a>
                                                    @endif
                                                </td>           
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    @can('user-view')
                                                    <a href="{{ route('users.show',$user->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endcan
                                                    <!-- Edit Button -->
                                                    @can('user-edit')
                                                    <a href="{{ route('users.edit',$user->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                    <!-- Delete Button -->
                                                    @can('user-delete')
                                                    <a href="{{ route('users.delete',$user->id)}}" id="delete" class="btn btn-danger btn-sm">
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
        </section>
    </div>
@endsection
