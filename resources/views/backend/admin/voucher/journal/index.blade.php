@extends('layouts.admin', ['pageTitle' => 'Journal Voucher List'])
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
                                    @can('journal-create')
                                    <a href="{{ route('journal-voucher.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Journal Voucher 
                                    </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Voucher No</th>
                                            <th>Company/Branch Name</th>
                                            {{-- <th>Ledger Name</th>
                                            <th>DR ({{ bdt() }})</th>
                                            <th>CR ({{ bdt() }})</th> --}}
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($journalVouchers as $key => $voucher)
                                            @php
                                                $isLatest = \Carbon\Carbon::parse($voucher->created_at)->isToday();
                                            @endphp
                                            <tr class="{{ $isLatest ? 'bg-success text-white' : 'bg-light' }}">
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $voucher->transaction_code }}</td>
                                                <td>{{ $voucher->company->name ?? get_company_info()->company_name }} / {{ $voucher->branch->name ?? get_company_info()->company_branch }}</td>
                                                {{-- <td>
                                                    <strong>{{ $voucher->details->pluck('ledger.name')->filter()->implode(', ') }}</strong>
                                                </td>
                                                <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($voucher->details->sum('debit'), 2) }}</td>
                                                <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($voucher->details->sum('credit'), 2) }}</td> --}}
                                                <td>{{ date('d M, Y', strtotime($voucher->transaction_date)) }}</td>
                                                <td class="col-2">
                                                    @can('journal-view')
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#voucherModal{{ $voucher->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endcan
                                                    @can('journal-edit')
                                                        <a href="{{ route('journal-voucher.edit', $voucher->id) }}" class="btn btn-sm btn-warning text-light">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('journal-delete')
                                                        <a id="delete" href="{{ route('journal-voucher.delete', $voucher->id) }}" class="btn btn-sm btn-danger delete-button">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!-- Total Row -->
                                    {{-- <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total:</th>
                                            <th class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($totalDebit, 2) }}</th>
                                            <th class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($totalCredit, 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot> --}}
                                </table>

                                <!-- Move all modals outside the table -->
                                @foreach ($journalVouchers as $voucher)
                                    <div class="modal fade" id="voucherModal{{ $voucher->id }}" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel{{ $voucher->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Voucher No - {{ $voucher->transaction_code }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Invoice Details -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>Company Name:</strong> {{ $voucher->company->name ?? get_company_info()->company_name }}<br>
                                                            <strong>Branch Name:</strong> {{ $voucher->branch->name ?? get_company_info()->company_branch  }}<br>
                                                        </div>
                                                        <div class="col-md-6 text-right">
                                                            <strong>Date:</strong> {{ date('d M, Y', strtotime($voucher->transaction_date)) }}<br>
                                                            <strong>Status:</strong> 
                                                            @php
                                                                $statusLabels = [
                                                                    0 => ['label' => 'Draft', 'class' => 'badge-secondary'],
                                                                    1 => ['label' => 'Pending', 'class' => 'badge-warning'],
                                                                    2 => ['label' => 'Approved', 'class' => 'badge-success']
                                                                ];
                                                            @endphp
                                                            <span class="badge {{ $statusLabels[$voucher->status]['class'] ?? 'badge-primary' }}">
                                                                {{ $statusLabels[$voucher->status]['label'] ?? 'Unknown' }}
                                                            </span>
                                                        </div>

                                                    </div>

                                                    <hr>

                                                    <!-- Invoice Table (Only inside modal) -->
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="table-primary">
                                                                <th>SL</th>
                                                                <th>Ledger Name</th>
                                                                <th>Reference No</th>
                                                                <th>Description</th>
                                                                <th class="text-end">Debit</th>
                                                                <th class="text-end">Credit</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($voucher->details as $key => $detail)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $detail->ledger->name ?? 'N/A' }}</td>
                                                                    <td>{{ $detail->reference_no ?? 'N/A' }}</td>
                                                                    <td>{{ $detail->description ?? 'N/A' }}</td>
                                                                    <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($detail->debit, 2) }}</td>
                                                                    <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($detail->credit, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th colspan="4" class="text-right">Total:</th>
                                                                <th class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($voucher->details->sum('debit'), 2) }}</th>
                                                                <th class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($voucher->details->sum('credit'), 2) }}</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <!-- Close Button -->
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Close
                                                    </button>

                                                    <!-- Print Button -->
                                                    <!-- <button type="button" class="btn btn-primary" onclick="printInvoice('{{ $voucher->id }}')">
                                                        <i class="fas fa-print"></i> Print Invoice
                                                    </button> -->
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

    <!-- Modal for Viewing Voucher Details -->
    <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="voucherModalLabel">Voucher Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ledger Name</th>
                                <th>Reference No</th>
                                <th>Description</th>
                                <th>Debit (৳)</th>
                                <th>Credit (৳)</th>
                            </tr>
                        </thead>
                        <tbody id="voucherDetails">
                            <!-- Voucher details will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<script>
    function printInvoice(voucherId) {
        var modalContent = document.querySelector(`#voucherModal${voucherId} .modal-body`).innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = modalContent;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload();
    }
</script>
<script>
    $(document).ready(function () {
        $('#file-upload').on('change', function (e) {
            // Get the file name
            var fileName = e.target.files[0].name;
            // Set the label text
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush
