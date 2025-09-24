@extends('layouts.admin', ['pageTitle' => 'Receive Payment List'])
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
                                    <a href="{{ route('project.receipt.payment.create') }}"
                                        class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Receive
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Invoice No</th>
                                            <th>Customer Name</th>
                                            <th>Pay Amount</th>
                                            <th>Payment Method</th>
                                            <th>Payment Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($receipts as $key => $receipt)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $receipt->invoice_no ?? 'N/A' }}</td>
                                                <td>{{ $receipt->client->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($receipt->pay_amount, 2) }}</td>
                                                <td>
                                                    @if ($receipt->payment_method == 'cash')
                                                        Cash
                                                    @elseif($receipt->payment_method == 'bank')
                                                        Bank
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $receipt->payment_date }}</td>
                                                <td>
                                                    <!-- Print Button -->
                                                    {{-- <button type="button" class="btn btn-info btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#printModal{{ $receipt->id }}">
                                                        <i class="fas fa-print"></i>
                                                    </button> --}}
                                                    
                                                    <!-- View Button -->
                                                    <a href="{{ route('project.receipt.payment.show', ['invoice_no' => $receipt->invoice_no]) }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('project.receipt.payment.edit', $receipt->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('project.receipt.payment.destroy', $receipt->id) }}"
                                                        id="delete" class="btn btn-danger btn-sm">
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
            </div>
        </section>
    </div>

   @foreach($receipts as $receipt)
<div class="modal fade" id="printModal{{ $receipt->id }}" tabindex="-1" role="dialog" aria-labelledby="printModalLabel{{ $receipt->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="printModalLabel{{ $receipt->id }}">
                    <i class="fas fa-receipt"></i> Payment Receipt
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body" id="voucherContent{{ $receipt->id }}">
                <div class="invoice p-4 border">
                    
                    <!-- Company Logo & Name -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <img src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                                 alt="Company Logo" style="height: 60px;">
                            <h4 class="mt-2">{{ get_company()->name ?? '' }}</h4>
                        </div>
                        <div class="col-6 text-right">
                            <h5 class="text-muted">Receipt Voucher</h5>
                            <p><strong>Ref No:</strong> {{ $receipt->invoice_no ?? '' }}</p>
                            <p><strong>Date:</strong> {{ $receipt->payment_date }}</p>
                        </div>
                    </div>
                    <hr>

                    <!-- Company & Client Info -->
                    <div class="row invoice-info mb-3">
                        <div class="col-sm-6">
                            <h6 class="font-weight-bold">From:</h6>
                            <address>
                                {{ get_company()->name ?? '' }}<br>
                                {{ get_company()->address ?? '' }}<br>
                                Phone: {{ get_company()->phone ?? '' }}<br>
                                Email: {{ get_company()->email ?? '' }}
                            </address>
                        </div>
                        <div class="col-sm-6 text-right">
                            <h6 class="font-weight-bold">To:</h6>
                            <address>
                                {{ $receipt->client->name }}<br>
                                {{ $receipt->client->address ?? '' }}<br>
                                Phone: {{ $receipt->client->phone ?? '' }}<br>
                                Email: {{ $receipt->client->email ?? '' }}
                            </address>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-bordered text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Payment Method</th>
                                        <th>Amount Received</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            @if($receipt->payment_method == 'cash') Cash
                                            @elseif($receipt->payment_method == 'bank') Bank
                                            @else N/A
                                            @endif
                                        </td>
                                        <td>{{ number_format($receipt->pay_amount, 2) }} ৳</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer / Signature -->
                    <div class="row mt-5">
                        <div class="col-6 text-left">
                            <p>__________________________</p>
                            <p>Received By</p>
                        </div>
                        <div class="col-6 text-right">
                            <p>__________________________</p>
                            <p>Authorized Signature</p>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="printInvoice('{{ $receipt->id }}')">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach



@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    
@endpush
