@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Client</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h3 class="card-title">All Clients</h3>
                        <a href="{{ route('admin.client.create') }}" class="btn btn-success float-right">Add Client</a> -->
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
