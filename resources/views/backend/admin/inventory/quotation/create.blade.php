@extends('layouts.admin', ['pageTitle' => 'Quotation Create'])

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
                                    <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('quotations.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label for="client_id" class="form-label">Client Name
                                                @error('client_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                <select class="form-control select2" id="client_id" name="client_id">
                                                    <option value="">Select Client</option>
                                                    @foreach ($clients as $client)
                                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                            {{ $client->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="total_amount" class="form-label">Total Amount
                                                @error('total_amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" placeholder="Enter Total Amount">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label for="quotation_date" class="form-label">Quotation Date
                                                @error('quotation_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                <input type="text" id="date" name="quotation_date" class="form-control @error('quotation_date') is-invalid @enderror" value="{{ old('quotation_date', now()->format('Y-m-d')) }}" />
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
                                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label">Description
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                                <i class="fas fa-plus"></i> Add Quotation
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
            placeholder: "Select Client",
            allowClear: true
        });
    });
</script>
@endpush
