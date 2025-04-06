@extends('layouts.admin', ['pageTitle' => 'Purchase List'])
@section('admin')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('admin.purchase.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Purchase
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>PO No</th>
                                            <th>PO Date</th>
                                            <th>Vendor</th>
                                            <th>Total Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $purchase->invoice_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($purchase->invoice_date)->format('d F Y') }}</td>
                                                <td>{{ $purchase->supplier->name ?? 'N/A' }}</td> 
                                                <td>{{ bdt() }} {{ number_format($purchase->total, 2) }}</td>
                                                <td>{{ bdt() }} {{ number_format($purchase->paid_amount ?? 'N/A', 2) }}</td> 
                                                <td>{{ bdt() }} {{ number_format($purchase->total-$purchase->paid_amount, 2) }}</td> 
                                                <!-- Status column with Badge -->
                                                <td>
                                                    @if($purchase->paid_amount >= $purchase->total)
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($purchase->paid_amount > 0)
                                                        <span class="badge bg-warning">Partially Paid</span>
                                                    @else
                                                        <span class="badge bg-danger">Pending</span>
                                                    @endif
                                                </td>
                                                
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('admin.purchase.show', $purchase->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-success btn-sm view-purchase" data-id="{{ $purchase->id }}" data-toggle="modal" data-target="#purchaseDetailsModal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>                                                    
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('admin.purchase.edit', $purchase->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('admin.purchase.destroy', $purchase->id) }}" id="delete" class="btn btn-danger btn-sm">
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
    
<!-- Modal for creating a new supplier -->
@include('backend.admin.inventory.purchase.view_modal')

@endsection

@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).ready(function () {
        $('.view-purchase').on('click', function () {
            var purchaseId = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.purchase.view') }}",
                type: "GET",
                data: { id: purchaseId },
                success: function (response) {
                    $('#purchaseDetailsContent').html(response);
                },
                error: function () {
                    alert('Failed to load purchase details.');
                }
            });
        });
    });
</script>
@endpush
