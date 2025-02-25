@extends('layouts.admin', ['pageTitle' => 'Work Order Edit'])

@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
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
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('workorders.index') }}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('workorders.update', $workorder->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="quotation_id" class="form-label">Quotation ID
                                                @error('quotation_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-file-alt"></i></span>
                                                <select class="form-control select2" id="quotation_id" name="quotation_id">
                                                    <option value="">Select Quotation</option>
                                                    @foreach ($quotations as $quotation)
                                                        <option value="{{ $quotation->id }}" {{ $workorder->quotation_id == $quotation->id ? 'selected' : '' }}>
                                                            {{ $quotation->quotation_number }}
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
                                                    <option value="In Progress" {{ $workorder->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Completed" {{ $workorder->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="Cancelled" {{ $workorder->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="start_date" class="form-label">Start Date
                                                @error('start_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                <input type="date" class="form-control" id="date" name="start_date" value="{{ old('start_date', $workorder->start_date) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="end_date" class="form-label">End Date
                                                @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                <input type="date" class="form-control" id="to_date" name="end_date" value="{{ old('end_date', $workorder->end_date) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label for="remarks" class="form-label">Remarks
                                                @error('remarks')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                                <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $workorder->remarks) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-save"></i> Update Work Order
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
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Quotation",
            allowClear: true
        });
    });
</script>
@endpush
