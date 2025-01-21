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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item active">Income</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- 
                    <div class="card-header">
                        <h3 class="card-title">DataTable with minimal features & hover style</h3>
                    </div> 
                    -->
                    
                    <div class="card-body">

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Payer</th>
                                    <th>Method</th>
                                    <th>Settings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>19-01-2025</td>
                                    <td>Company Sales Account</td>
                                    <td>৳ 0.00</td>
                                    <td>৳ 5,000.00</td>
                                    <td>test</td>
                                    <td>Cash</td>
                                    <td>
                                        <a type="button" class="btn btn-primary" href="{{ route('admin.invoiceDetails') }}">
                                            <i class="fa-regular fa-file-lines" style="margin-right: 8px;"></i>View
                                        </a> 
                                       
                                        <button type="submit" class="btn btn-info"><i class="fa-solid fa-download"></i></button>
                                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>

@endsection