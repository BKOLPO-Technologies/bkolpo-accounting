@extends('layouts.admin')
@section('admin')
<main class="app-main"> 

    <div class="app-content-header"> 
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $pageTitle ?? 'N/A' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div> 
    </div>

    <div class="app-content"> 
        <div class="container-fluid"> 
            <div class="col-md-12"> 
                <div class="card card-primary card-outline mb-4"> 
                    <div class="card-header">
                        <div class="card-title">{{ $pageTitle ?? 'Manage Roles & Permissions' }}</div>
                    </div> 
                    <form> 
                        <div class="card-body">
                            <div class="mb-3"> 
                                <label class="form-label">Role Name</label> 
                                <div class="input-group">
                                    <input type="text" class="form-control" name="role_name" placeholder="Enter role name" required>
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Role Description</label> 
                                <div class="input-group">
                                    <input type="text" class="form-control" name="role_description" placeholder="Enter role description">
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Permissions</label> 
                                <div class="input-group">
                                    <select class="form-select" required>
                                        <option>Permission 1</option>
                                        <option>Permission 2</option>
                                        <option>Permission 3</option>
                                        <option>Permission 4</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select at least one permission.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center"> 
                                <div>
                                    Active
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activeToggle" name="activeToggle" checked>
                                    <label class="form-check-label" for="activeToggle"></label>
                                </div>
                            </div>
                        </div> 

                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary">Save Role</button> 
                        </div> 
                    </form> 
                </div> 
                
                <div class="card card-warning card-outline mb-4"> 
                    <div class="card-header">
                        <div class="card-title">Existing Roles</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Role Name</th>
                                        <th>Description</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Admin</td>
                                        <td>Administrator role with full access</td>
                                        <td>All Permissions</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm me-2" title="Edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>User</td>
                                        <td>Basic user role with limited access</td>
                                        <td>Read Permissions</td>
                                        <td><span class="badge bg-danger">Inactive</span></td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm me-2" title="Edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Add more roles as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>

</main>
@endsection
