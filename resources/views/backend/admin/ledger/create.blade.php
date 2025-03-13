@extends('layouts.admin', ['pageTitle' => 'Ledger Create'])

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
                                    <a href="{{ route('ledger.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                              <form method="POST" action="{{ route('ledger.store') }}" enctype="multipart/form-data">
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
                                              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Ledger Name">
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
                                                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->group_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- <div class="col-md-6 mb-2">
                                            <label for="sub_group_id" class="form-label">Select Sub Group
                                                @error('sub_group_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>

                                            <select class="form-control" id="sub_group_id" name="sub_group_id">
                                                <option value="">Select Sub Group</option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}" {{ old('sub_group_id') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->group_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}} 

                                        <div class="col-md-6 mb-2">
                                            <label for="sub_group_id" class="form-label">Select Sub Group</label>
                                            <select class="form-control" id="sub_group_id" name="sub_group_id">
                                                <option value="">Select Sub Group</option>
                                            </select>
                                        </div>

                                        {{-- <div class="col-md-6 mb-2">
                                          <label for="debit" class="form-label">Opening Balance (DR)
                                              @error('debit')
                                                  <span class="text-danger">{{ $message }}</span>
                                              @enderror
                                          </label>
                                          <div class="input-group">
                                              <span class="input-group-text"><i class="fa fa-money-bill-wave"></i></span>
                                              <input type="number" min="0" class="form-control" id="debit" name="debit" value="{{ old('debit') }}" placeholder="Enter Opening Balance Debit">
                                          </div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="credit" class="form-label">Ending Balance (CR)
                                                @error('credit')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-money-bill-wave"></i></span>
                                                <input type="number" min="0" class="form-control" id="credit" name="credit" value="{{ old('credit') }}" placeholder="Enter Ending Balance Credit">
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
                                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-plus"></i> Add Ledger</button>
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
    // select 2
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
