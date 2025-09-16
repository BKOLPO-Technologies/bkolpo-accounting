@extends('layouts.admin', ['pageTitle' => 'Advance Payment Receive List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
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
                                    <a href="{{ route('project.advance.receipt.payment.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Receive
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Reference No</th>
                                            <th>Customer Name</th>
                                            <th>Pay Amount</th>
                                            <th>Payment Method</th>  
                                            <th>Payment Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($receipts as $key => $receipt)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $receipt->invoice_no ?? 'N/A' }}</td>
                                                <td>{{ $receipt->client->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($receipt->pay_amount, 2) }}</td>
                                                <td>
                                                    @if($receipt->payment_method == 'cash')
                                                        Cash
                                                    @elseif($receipt->payment_method == 'bank')
                                                        Bank
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $receipt->payment_date }}</td>
                                                <td>
                                                    <!-- View Button -->
                                                    <a href="{{ route('project.advance.receipt.payment.show', ['invoice_no' => $receipt->invoice_no]) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('project.advance.receipt.payment.edit', $receipt->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('project.advance.receipt.payment.destroy', $receipt->id) }}" id="delete" class="btn btn-danger btn-sm">
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
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
