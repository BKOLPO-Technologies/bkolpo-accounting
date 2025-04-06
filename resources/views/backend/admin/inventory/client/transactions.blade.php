@extends('layouts.admin', ['pageTitle' => 'Supplier Transactions'])

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.client.index') }}">Clients</a></li>
                        <li class="breadcrumb-item active">Transactions</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline shadow-lg">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $client->name }} - Payment Transactions</h4>
                        <a href="{{ route('admin.client.view',$client->id) }}" class="btn btn-sm btn-danger rounded-0">
                            <i class="fa-solid fa-arrow-left"></i> Back To List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Invoice No</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Date</th>
                                    {{-- <th>Status</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp 
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ $transaction->invoice_no }}</td>
                                        <td>{{ bdt() }} {{ number_format($transaction->paid_amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->payment_date)->format('d F Y') }}</td>
                                        {{-- <td>{{ ucfirst($transaction->status) }}</td> --}}
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
