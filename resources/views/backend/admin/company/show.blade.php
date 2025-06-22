@extends('layouts.admin', [$pageTitle => 'Company View'])

@section('admin')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? '' }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                                <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                <a href="{{ route('company.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $company->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Branch</th>
                                        <td>{{ $company->branch->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $company->address ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td>{{ $company->city ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td>{{ $company->country ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>State</th>
                                        <td>{{ $company->state ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Post Code</th>
                                        <td>{{ $company->post_code ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $company->phone ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $company->email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $company->description ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Currency Symbol</th>
                                        <td>{{ $company->currency_symbol ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fiscal Year</th>
                                        <td>{{ $company->fiscal_year ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>VAT</th>
                                        <td>{{ $company->vat ?? '' }} %</td>
                                    </tr>
                                    <tr>
                                        <th>TAX</th>
                                        <td>{{ $company->tax ?? '' }} %</td>
                                    </tr>
                                    <tr>
                                        <th>Company Logo</th>
                                        <td>
                                            <div class="col-md-1">
                                            <img
                                                id="logoPreview"
                                                src="{{ !empty($company->logo) ? url('upload/company/' . $company->logo) : url(asset('backend/logo.jpg')) }}" 
                                                alt="Logo"
                                                style="width: 100%; height: 60px; border: 1px solid #ddd; border-radius: 5px;">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($company->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>                                     
                                </tbody>
                            </table>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
