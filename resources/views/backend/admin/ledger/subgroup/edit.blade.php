@extends('layouts.admin', ['pageTitle' => 'Edit Ledger Sub Group'])

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
                                    <a href="{{ route('ledger.sub.group.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('ledger.sub.group.update', $subGroup->id) }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="subgroup_name" class="form-label">Sub Group Name
                                                @error('subgroup_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                <input type="text" class="form-control" id="subgroup_name" name="subgroup_name" value="{{ old('subgroup_name', $subGroup->subgroup_name) }}" placeholder="Enter Sub Group Name">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="ledger_group_id" class="form-label">Group Name
                                                @error('ledger_group_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-folder"></i></span>
                                                <select class="form-control" id="ledger_group_id" name="ledger_group_id">
                                                    <option value="">Select Group</option>
                                                    @foreach($groups as $group)
                                                        <option value="{{ $group->id }}" {{ old('ledger_group_id', $subGroup->ledger_group_id) == $group->id ? 'selected' : '' }}>
                                                            {{ $group->group_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="status" class="form-label">Status
                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" {{ old('status', $subGroup->status) == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status', $subGroup->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Update Ledger Sub Group
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
<!-- Select2 Initialization JS -->
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select a Ledger",
            allowClear: true
        });
    });
</script>
@endpush
