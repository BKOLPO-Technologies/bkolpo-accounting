@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Product View</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" style="text-decoration: none; color: black;">Product</a></li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">

                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Product View</h4>
                                <a href="{{ route('admin.product.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $product->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Product Code</th>
                                        <td>{{ $product->product_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category Name</th>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Unit Name</th>
                                        <td>{{ $product->unit->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>{{ $product->price ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $product->description ?? 'N/A' }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Product Picture</th>
                                        <td>
                                            <div class="col-md-1">
                                            <img
                                                id="logoPreview"
                                                src="{{ !empty($product->image) ? url('upload/inventory/products/' . $product->image) : url(asset('backend/logo.jpg')) }}" 
                                                alt="Logo"
                                                style="width: 100%; height: 60px; border: 1px solid #ddd; border-radius: 5px;">
                                                {{-- <img id="imagePreview" src="{{ asset('upload/inventory/products/' . $product->image) }}" alt="Image Preview" style="display:block; margin-top: 10px; height:80px; width: 80px;"> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($product->status)
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