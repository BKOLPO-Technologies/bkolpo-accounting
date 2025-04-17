@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Product List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header">
                        <h3 class="card-title">All Products</h3>
                        <a href="{{ route('admin.product.create') }}" class="btn btn-success float-right">
                            <i class="fas fa-plus fa-sm"></i> Add Product
                        </a>
                    </div>

                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Category Name</th>
                                    <th>Price</th>
                                    {{-- <th>Quantity</th> --}}
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key=> $product)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->price }}</td>
                                    {{-- <td>{{ $product->quantity }}</td> --}}
                                    <td>
                                        @if($product->image)
                                            <img src="{{ (!empty($product->image)) ? url('upload/inventory/products/'.$product->image):url('https://via.placeholder.com/70x60') }}" width="50">
                                        @else
                                            No Image
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.product.view', $product->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.product.destroy', $product->id) }}" id="delete" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div> 
        </div>

    </section>

</div>

@endsection
