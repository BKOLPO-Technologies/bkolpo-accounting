@extends('layouts.admin', ['pageTitle' => 'Puchase'])
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                            <a href="{{ route('admin.purchase.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div style="text-align: center;">
                            <h1>Purchase Order</h1>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <h5>Po</h5>
                            </div>
                            <div class="col-sm-6" style="text-align: right;">
                                <h5>Date</h5>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center;">
                            <h5 style="margin-right: 10px;">Supplier</h5>
                            <select name="supplier" id="supplier">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Amount</th>
                                    <th>Unknown</th>
                                    <th>Unknown</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->price }}</td>
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
