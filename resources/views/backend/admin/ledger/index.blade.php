@extends('layouts.admin', ['pageTitle' => 'Ledger List'])
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
                                    @can('ledger-create')
                                    <a href="{{ route('ledger.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Ledger
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Group Name</th> 
                                            <th>Opening DR (৳)</th>
                                            <th>DR (৳)</th>
                                            <th>CR (৳)</th>
                                            <th>Current DR (৳)</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $totalOpeningDr = 0;
                                        $totalDr = 0;
                                        $totalCr = 0;
                                        $totalCurrentDr = 0;
                                    @endphp
                                    <tbody>
                                        @foreach($ledgers as $ledger) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>
                                                    {{ $ledger->created_at ? \Carbon\Carbon::parse($ledger->created_at)->format('d F Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $ledger->name }}</td>
                                                <td>
                                                    @foreach($ledger->groups as $group)
                                                        <span class="badge badge-info">{{ $group->group_name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>৳{{ number_format($ledger->debit, 2) }}</td>
                                                <td>৳{{ number_format($ledger->ledgerSums['debit'], 2) }}</td>  
                                                <td>৳{{ number_format($ledger->ledgerSums['credit'], 2) }}</td>
                                                <!-- <td>৳{{ number_format($ledger->debit + $ledger->ledgerSums['debit'] - $ledger->ledgerSums['credit'], 2) }}</td> -->
                                                <td>
                                                    ৳{{ number_format(
                                                        ($ledger->debit > 0)
                                                            ? ($ledger->debit + $ledger->ledgerSums['debit'] - $ledger->ledgerSums['credit']) // If opening balance is Debit
                                                            : ($ledger->ledgerSums['debit']), // If opening balance is Credit
                                                        2
                                                    ) }}
                                                    @php
                                                        // Add the current debit to total
                                                        $totalCurrentDr += ($ledger->debit > 0)
                                                            ? ($ledger->debit + $ledger->ledgerSums['debit'] - $ledger->ledgerSums['credit'])
                                                            : ($ledger->ledgerSums['debit']);
                                                    @endphp
                                                </td>
                                                <td>
                                                    @if($ledger->status == 1)
                                                        <a href="#" class="badge badge-success">
                                                            <span class="badge bg-success">Active</span>
                                                        </a>
                                                    @else
                                                        <a href="#" class="badge badge-danger">
                                                            <span class="badge bg-danger">Inactive</span>
                                                        </a>
                                                    @endif
                                                </td>           
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    @can('ledger-view')
                                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#ledgerModal{{ $ledger->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @endcan
                                                    <!-- Edit Button -->
                                                    @can('ledger-edit')
                                                    <a href="{{ route('ledger.edit', $ledger->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                    <!-- Delete Button -->
                                                    @can('ledger-delete')
                                                    <a href="{{ route('ledger.delete', $ledger->id)}}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!-- Total Row -->
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-right">Total:</th>
                                            <th>৳{{ number_format($totals['totalDebit'], 2) }}</th> 
                                            <th>৳{{ number_format($totals['totalCredit'], 2) }}</th> 
                                            <th>৳{{ number_format($totalCurrentDr, 2) }}</th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Move all modals outside the table -->
                                @foreach ($ledgers as $ledger)
                                    <div class="modal fade" id="ledgerModal{{ $ledger->id }}" tabindex="-1" role="dialog" aria-labelledby="ledgerModalLabel{{ $ledger->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ledger Name - {{ $ledger->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Voucher Details Table -->
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>SL</th>
                                                                <th>Reference No</th>
                                                                <th>Description</th>
                                                                <th>Date</th>
                                                                <th class="text-end">Debit (৳)</th>
                                                                <th class="text-end">Credit (৳)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $totalDebit = 0;
                                                                $totalCredit = 0;
                                                                $openingBalance = $ledger->debit;
                                                            @endphp
                                                            
                                                            @foreach ($ledger->journalVoucherDetails as $index => $voucherDetail)
                                                                @php
                                                                    $totalDebit += $voucherDetail->debit;
                                                                    $totalCredit += $voucherDetail->credit;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $voucherDetail->reference_no }}</td>
                                                                    <td>{{ $voucherDetail->description }}</td>
                                                                    <td> {{ date('d M, Y', strtotime($voucherDetail->journalVoucher->transaction_date)) }}</td>
                                                                    <td>৳{{ number_format($voucherDetail->debit, 2) }}</td>
                                                                    <td>৳{{ number_format($voucherDetail->credit, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" class="text-right">Total:</th>
                                                                <th>৳{{ number_format($totalDebit, 2) }}</th>
                                                                <th>৳{{ number_format($totalCredit, 2) }}</th>
                                                            </tr>
                                                            <!-- Opening Balance -->
                                                            <tr>
                                                                <th colspan="5" class="text-right">Opening Balance:</th>
                                                                <th colspan="2">৳{{ number_format($ledger->debit, 2) }}</th>
                                                            </tr>

                                                            <!-- Current Balance -->
                                                            <tr>
                                                                <th colspan="5" class="text-right">Current Balance:</th>
                                                                <th colspan="2">
                                                                    ৳{{ number_format($openingBalance + $totalDebit - $totalCredit, 2) }}
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- Close Button -->
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
