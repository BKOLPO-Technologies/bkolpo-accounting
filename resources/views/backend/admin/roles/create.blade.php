@extends('layouts.admin', ['pageTitle' => 'Role Create'])

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
                                    <a href="{{ route('roles.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('roles.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label">Name
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Role Name">
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <div class="form-group">
                                                <strong>Permission:</strong>
                                                
                                                <!-- Global Select All checkbox (outside card) -->
                                                <div class="form-group clearfix mt-3">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" id="globalSelectAll">
                                                        <label for="globalSelectAll">
                                                            Global Select All
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <!-- Role Menu Card -->
                                                <div class="card card-info card-outline">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <!-- Role Menu title on the left -->
                                                        <h5 class="card-title mb-0">Role Menu</h5>

                                                        <!-- Select All checkbox for the Role Menu Card -->
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllRoleCard">
                                                            <label for="selectAllRoleCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            <!-- Loop through permissions and display role-menu permissions -->
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'role-')) 
                                                                    <!-- Show role-related permissions -->
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox role-menu-checkbox" id="checkboxRole{{ $index }}">
                                                                        <label for="checkboxRole{{ $index }}">{{ ucfirst(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- User Menu Card -->
                                                <div class="card card-info card-outline mt-4">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <!-- User Menu title on the left -->
                                                        <h5 class="card-title mb-0">User Menu</h5>

                                                        <!-- Select All checkbox for the User Menu Card -->
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllUserCard">
                                                            <label for="selectAllUserCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            <!-- Loop through permissions and display user-menu permissions -->
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'user-')) 
                                                                    <!-- Show user-related permissions -->
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox user-menu-checkbox" id="checkboxUser{{ $index }}">
                                                                        <label for="checkboxUser{{ $index }}">{{ ucfirst(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Repeat for more cards -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-plus"></i> Add Role
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script>
    // Global Select All (outside all cards)
    document.querySelector('#globalSelectAll').addEventListener('change', function() {
        const isChecked = this.checked;
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });

    // Card Select All (selects all checkboxes inside a card)
    // JavaScript to handle the "Select All" functionality for each card
    document.addEventListener("DOMContentLoaded", function() {
        // Handle select all checkbox for Role Menu Card
        const selectAllRoleCard = document.getElementById('selectAllRoleCard');
        const roleMenuCheckboxes = document.querySelectorAll('.role-menu-checkbox');
        
        // Listen for change event on the Select All checkbox for the Role Menu Card
        selectAllRoleCard.addEventListener('change', function() {
            roleMenuCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllRoleCard.checked; // Set checkbox checked state based on the "Select All"
            });
        });

        // Handle select all checkbox for User Menu Card
        const selectAllUserCard = document.getElementById('selectAllUserCard');
        const userMenuCheckboxes = document.querySelectorAll('.user-menu-checkbox');
        
        // Listen for change event on the Select All checkbox for the User Menu Card
        selectAllUserCard.addEventListener('change', function() {
            userMenuCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllUserCard.checked; // Set checkbox checked state based on the "Select All"
            });
        });
    });
</script>
@endpush
