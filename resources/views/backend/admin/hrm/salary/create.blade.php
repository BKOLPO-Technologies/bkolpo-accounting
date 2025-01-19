@extends('layouts.admin', [$pageTitle => 'Leave Application Create'])

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
                <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('salary.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('salary.store') }}" method="POST">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-end">
                                            <div class="input-group me-1" data-bs-placement="bottom" data-bs-toggle="tooltip-primary" title="Month">
                                                <label class="text-white" for="month">Month</label>
                                                <select name="month" id="month" class="form-control select2 month" required>
                                                    <option {{ date('m') == 1 ? 'selected' : '' }} value="1">January</option>
                                                    <option {{ date('m') == 2 ? 'selected' : '' }} value="2">February</option>
                                                    <option {{ date('m') == 3 ? 'selected' : '' }} value="3">March</option>
                                                    <option {{ date('m') == 4 ? 'selected' : '' }} value="4">April</option>
                                                    <option {{ date('m') == 5 ? 'selected' : '' }} value="5">May</option>
                                                    <option {{ date('m') == 6 ? 'selected' : '' }} value="6">June</option>
                                                    <option {{ date('m') == 7 ? 'selected' : '' }} value="7">July</option>
                                                    <option {{ date('m') == 8 ? 'selected' : '' }} value="8">August</option>
                                                    <option {{ date('m') == 9 ? 'selected' : '' }} value="9">September</option>
                                                    <option {{ date('m') == 10 ? 'selected' : '' }} value="10">October</option>
                                                    <option {{ date('m') == 11 ? 'selected' : '' }} value="11">November</option>
                                                    <option {{ date('m') == 12 ? 'selected' : '' }} value="12">December</option>
                                                </select>
                                            </div>
                                            <div class="input-group ms-1" data-bs-placement="bottom" data-bs-toggle="tooltip-primary" title="Year">
                                                <label class="text-white" for="year">Year</label>
                                                <select name="year" id="year" class="form-control select2 year" required>
                                                    @for ($i = date('Y') - 2; $i <= 2024; $i++)
                                                        <option {{ date('Y') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Staff Name</th>
                                                            <th>Salary</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($staffs as $staff)
                                                            <tr>
                                                                <td width="50%">{{ $staff->full_name }}</td>
                                                                <td width="50%">
                                                                    <input type="hidden" name="staff_id[]" value="{{ $staff->id }}">
                                                                    <input type="number" min="0" step="any" name="salary[]" value="{{ $staff->salary ?? 0 }}" class="form-control form-control-sm bg-transparent">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="d-flex justify-content-center mt-3">
                                                    <button class="btn btn-success w-50"> <i class="fas fa-paper-plane"></i> Salary Create</button>
                                                </div>
                                            </div>
                                        </div>
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
    // Initialize Select2
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
