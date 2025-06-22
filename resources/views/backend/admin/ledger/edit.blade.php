@extends('layouts.admin', ['pageTitle' => 'Ledger Edit'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? ''}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? ''}}</li>
                  </ol>
                </div>
              </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                    <a href="{{ route('ledger.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                              <form method="POST" action="{{ route('ledger.update',$ledger->id) }}" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="name" class="form-label">Name
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $ledger->name }}" placeholder="Enter Ledger Name">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="group_id" class="form-label">Select Group
                                            @error('group_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>

                                        <select class="form-control" id="group_id" name="group_id">
                                            <option value="">Select Group</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" 
                                                    {{ old('group_id', $selectedGroupId) == $group->id ? 'selected' : '' }}>
                                                    {{ $group->group_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-6 mb-2">
                                        <label for="sub_group_id" class="form-label">Select Sub Group</label>
                                        <select class="form-control" id="sub_group_id" name="sub_group_id">
                                            <option value="">Select Sub Group</option>
                                            @foreach($subGroups as $group)
                                                <option value="{{ $group->id }}" 
                                                    {{-- {{ old('group_id', $subGroup->ledger_group_id) == $group->id ? 'selected' : '' }}> --}}
                                                    {{ old('group_id', $selectedSubGroupId) == $group->id ? 'selected' : '' }}>
                                                    {{ $group->subgroup_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="col-md-6 mb-2">
                                        <label for="opening_balance" class="form-label">Opening Balance
                                            @error('opening_balance')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-money-bill-wave"></i></span>
                                            <input type="number" min="0" class="form-control" id="opening_balance" name="opening_balance" value="{{ $ledger->opening_balance }}" placeholder="Enter Opening Balance">
                                        </div>
                                    </div> --}}

                                    
                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="form-label">Status
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1" {{ $ledger->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $ledger->status == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row mt-3">
                                      <div class="col-lg-12">
                                          <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-paper-plane"></i> Update Ledger</button>
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
    
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select roles",
            allowClear: true
        });
    });

    $(document).ready(function () {
        $('#group_id').change(function () {
            let groupId = $(this).val();

            if (groupId) {
                $.ajax({
                    url: "/get-sub-groups/" + groupId,
                    type: "GET",
                    success: function (data) {
                        $('#sub_group_id').empty().append('<option value="">Select Sub Group</option>');
                        $.each(data, function (key, value) {
                            $('#sub_group_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    },
                    error: function () {
                        alert("Failed to load sub groups.");
                    }
                });
            } else {
                $('#sub_group_id').empty().append('<option value="">Select Sub Group</option>');
            }
        });
    });

</script>
@endpush
