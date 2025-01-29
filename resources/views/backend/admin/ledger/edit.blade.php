@extends('layouts.admin', ['pageTitle' => 'Ledger Edit'])

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
                                            <label for="group" class="form-label">Select Group</label>
                                            <select id="group" name="group_id[]" class="select2" multiple="multiple" style="width: 100%;">
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}" 
                                                        {{ in_array($group->id, $ledger->groups->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $group->group_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                      <div class="col-md-12 mb-2">
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
    // select 2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select roles",
            allowClear: true
        });
    });

</script>
@endpush
