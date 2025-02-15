@extends('layouts.admin', ['pageTitle' => 'Role Edit'])

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
                                <form method="POST" action="{{ route('roles.update', $role->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="name" class="form-label">Name
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" placeholder="Enter Role Name">
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

                                                <!-- Branch Menu Card -->
                                                <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <!-- Branch Menu title on the left -->
                                                        <h5 class="card-title mb-0">Branch Menu</h5>

                                                        <!-- Select All checkbox for the Branch Menu Card -->
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllBranchCard">
                                                            <label for="selectAllBranchCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            <!-- Loop through permissions and display branch-menu permissions -->
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'branch-')) 
                                                                    <!-- Show branch-related permissions -->
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox branch-menu-checkbox" id="checkboxBranch{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxBranch{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                 <!-- Company Menu Card -->
                                                 <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <!-- Company Menu title on the left -->
                                                        <h5 class="card-title mb-0">Company Menu</h5>

                                                        <!-- Select All checkbox for the Company Menu Card -->
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllCompanyCard">
                                                            <label for="selectAllCompanyCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            <!-- Loop through permissions and display company-menu permissions -->
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'company-')) 
                                                                    <!-- Show company-related permissions -->
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox company-menu-checkbox" id="checkboxCompany{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxCompany{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ledger Group Menu Card -->
                                                <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">Ledger Group Menu</h5>
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllLedgerGroupCard">
                                                            <label for="selectAllLedgerGroupCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'ledger-group-')) 
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox ledger-group-checkbox" id="checkboxLedgerGroup{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxLedgerGroup{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Ledger Menu Card -->
                                                <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">Ledger Menu</h5>
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllLedgerCard">
                                                            <label for="selectAllLedgerCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'ledger-') && !str_contains($value->name, 'ledger-group-')) 
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox ledger-checkbox" id="checkboxLedger{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxLedger{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Journal Menu Card -->
                                                <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">Journal Menu</h5>
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllJournalCard">
                                                            <label for="selectAllJournalCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            @foreach($permission as $index => $value)
                                                                @if(str_contains($value->name, 'journal-')) 
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox journal-checkbox" id="checkboxJournal{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxJournal{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Report Menu Card -->
                                                <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">Report Menu</h5>
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllReportCard">
                                                            <label for="selectAllReportCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            @foreach($permission as $index => $value)
                                                                @if(in_array($value->name, ['report-menu', 'report-list', 'trial-balnce-report', 'balance-shit-report'])) 
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox report-checkbox" id="checkboxReport{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxReport{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Role Menu Card -->
                                                <div class="card card-info card-outline shadow">
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
                                                                            class="permission-checkbox role-menu-checkbox" id="checkboxRole{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxRole{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- User Menu Card -->
                                                <div class="card card-info card-outline shadow mt-4">
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
                                                                            class="permission-checkbox user-menu-checkbox" id="checkboxUser{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxUser{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                  <!-- Settings Menu Card -->
                                                  <div class="card card-info card-outline mt-4 shadow">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h5 class="card-title mb-0">Settings Menu</h5>
                                                        <div class="ml-auto icheck-primary d-inline">
                                                            <input type="checkbox" class="select-all-in-card" id="selectAllSettingsCard">
                                                            <label for="selectAllSettingsCard">Select All</label>
                                                        </div>
                                                    </div>

                                                    <div class="card-body">
                                                        <div class="form-group clearfix">
                                                            @foreach($permission as $index => $value)
                                                                @if(in_array($value->name, ['setting-menu', 'dashboard-menu', 'setting-information','setting-information-edit', 'profile-view', 'password-change'])) 
                                                                    <div class="icheck-success d-inline mb-2 mr-3">
                                                                        <input type="checkbox" value="{{$value->id}}" name="permission[{{$value->id}}]" 
                                                                            class="permission-checkbox settings-checkbox" id="checkboxSettings{{ $index }}"
                                                                            @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                                        <label for="checkboxSettings{{ $index }}">{{ ucwords(str_replace('-', ' ', $value->name)) }}</label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-paper-plane"></i> Update Role
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
        // Handle select all checkbox for Branch Menu Card
        const selectAllBranchCard = document.getElementById('selectAllBranchCard');
        const branchMenuCheckboxes = document.querySelectorAll('.branch-menu-checkbox');
        
        // Listen for change event on the Select All checkbox
        selectAllBranchCard.addEventListener('change', function() {
            branchMenuCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllBranchCard.checked; // Set checkbox checked state based on "Select All"
            });
        });

        const selectAllCompanyCard = document.getElementById('selectAllCompanyCard');
        const companyMenuCheckboxes = document.querySelectorAll('.company-menu-checkbox');
        
        // Listen for change event on the Select All checkbox
        selectAllCompanyCard.addEventListener('change', function() {
            companyMenuCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCompanyCard.checked; // Set checkbox checked state based on "Select All"
            });
        });

        // Ledger Group Select All
        const selectAllLedgerGroupCard = document.getElementById('selectAllLedgerGroupCard');
        const ledgerGroupCheckboxes = document.querySelectorAll('.ledger-group-checkbox');

        selectAllLedgerGroupCard.addEventListener('change', function() {
            ledgerGroupCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllLedgerGroupCard.checked;
            });
        });

        // Ledger Select All
        const selectAllLedgerCard = document.getElementById('selectAllLedgerCard');
        const ledgerCheckboxes = document.querySelectorAll('.ledger-checkbox');

        selectAllLedgerCard.addEventListener('change', function() {
            ledgerCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllLedgerCard.checked;
            });
        });

        // Journal Select All
        const selectAllJournalCard = document.getElementById('selectAllJournalCard');
        const journalCheckboxes = document.querySelectorAll('.journal-checkbox');

        selectAllJournalCard.addEventListener('change', function() {
            journalCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllJournalCard.checked;
            });
        });

        // Report Select All
        const selectAllReportCard = document.getElementById('selectAllReportCard');
        const reportCheckboxes = document.querySelectorAll('.report-checkbox');

        selectAllReportCard.addEventListener('change', function() {
            reportCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllReportCard.checked;
            });
        });

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

        // Settings Select All
        const selectAllSettingsCard = document.getElementById('selectAllSettingsCard');
        const settingsCheckboxes = document.querySelectorAll('.settings-checkbox');

        selectAllSettingsCard.addEventListener('change', function() {
            settingsCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllSettingsCard.checked;
            });
        });
    });
</script>
@endpush
