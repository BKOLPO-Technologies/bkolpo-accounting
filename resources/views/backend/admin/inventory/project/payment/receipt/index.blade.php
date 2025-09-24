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
                                                    @foreach ($ledgers as $ledger)
                                                        @if ($receipt->ledger_id == $ledger->id)
                                                            {{ ucfirst($ledger->name) }}
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td>{{ $receipt->payment_date }}</td>
                                                <td>
                                                    <!-- Print Button -->
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#printModal{{ $receipt->id }}">
                                                        <i class="fas fa-print"></i>
                                                    </button>

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

    @foreach ($receipts as $receipt)
        @php
            $sale = App\Models\Sale::where('invoice_no', $receipt->invoice_no)->first();
        @endphp
        <div class="modal fade" id="printModal{{ $receipt->id }}" tabindex="-1" role="dialog"
            aria-labelledby="printModalLabel{{ $receipt->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="printModalLabel{{ $receipt->id }}">
                            <i class="fas fa-receipt"></i> Receipt
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" id="voucherContent{{ $receipt->id }}">
                        <div style="padding:20px; border:1px solid #ccc; font-family:Arial, sans-serif; font-size:14px;">

                            <!-- Company Logo & Name -->
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                                <div>
                                    <img src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}"
                                        alt="Company Logo" style="height:50px;">
                                </div>
                                <div style="text-align:right;">
                                    <h5 style="margin:0; color:#6c757d;">Receipt Voucher</h5>
                                    <p style="margin:0;"><strong>Reference No:</strong>
                                        {{ $sale->project->reference_no ?? '' }}</p>
                                    <p style="margin:0;"><strong>Receive Date:</strong> {{ \Carbon\Carbon::parse($receipt->payment_date)->format('d F Y') }}</p>
                                </div>
                            </div>

                            <hr style="margin:10px 0;">

                            <!-- Company & Client Info -->
                            <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
                                <div style="width:52%;">
                                    <h6 style="margin:0 0 5px 0; font-weight:bold;">From:</h6>
                                    <address style="margin:0;">
                                        {{ get_company()->name ?? '' }}<br>
                                        {{ get_company()->address ?? '' }}<br>
                                        Phone: {{ get_company()->phone ?? '' }}<br>
                                        Email: {{ get_company()->email ?? '' }}
                                    </address>
                                </div>
                                <div style="width:44%; text-align:left;">
                                    <h6 style="margin:0 0 5px 0; font-weight:bold;">To:</h6>
                                    <address style="margin:0;">
                                        {{ $receipt->client->name }}<br>
                                        {{ $receipt->client->address ?? '' }}<br>
                                        Phone: {{ $receipt->client->phone ?? '' }}<br>
                                        Email: {{ $receipt->client->email ?? '' }}
                                    </address>
                                </div>
                            </div>

                            <!-- Payment Summary -->
                            <div style="margin-top:20px;">
                                <table style="width:100%; border-collapse:collapse; text-align:center;">
                                    <thead>
                                        <tr style="background:#f8f9fa;">
                                            <th style="border:1px solid #dee2e6; padding:6px;">Invoice No</th>
                                            <th style="border:1px solid #dee2e6; padding:6px;">Payment Method</th>
                                            <th style="border:1px solid #dee2e6; padding:6px;">Amount Received</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border:1px solid #dee2e6; padding:6px;">
                                                {{ $receipt->invoice_no ?? '' }}
                                            </td>
                                            <td style="border:1px solid #dee2e6; padding:6px;">
                                                @foreach ($ledgers as $ledger)
                                                    @if ($receipt->ledger_id == $ledger->id)
                                                        {{ ucfirst($ledger->name) }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td style="border:1px solid #dee2e6; padding:6px;">
                                                {{ bdt() }} {{ number_format($receipt->pay_amount, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                               <div>
    <strong style="display:inline-block; padding-top:20px;">Amount in Words:</strong>
    <strong>{{ convertNumberToWords($receipt->pay_amount) }}</strong>
</div>

                            </div>

                            <!-- Footer / Signature -->
                            <div style="display:flex; justify-content:space-between; margin-top:60px;">
                                <div style="text-align:left;">
                                    <p style="margin:0;">__________________________</p>
                                    <p style="margin:0;">Received By</p>
                                </div>
                                <div style="text-align:right;">
                                    <p style="margin:0;">__________________________</p>
                                    <p style="margin:0;">Authorized Signature</p>
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
  <script>
    function printInvoice(id) {
        let printContents = document.getElementById('voucherContent' + id).outerHTML;

        // Ensure image paths are absolute (prepend domain if missing)
        const baseUrl = window.location.origin + '/';
        printContents = printContents.replace(/src="\//g, 'src="' + baseUrl);

        const html = `
        <html>
        <head>
            <title>Print Receipt</title>
            <style>
                @media print {
                    body {
                        margin: 15mm 10mm 15mm 10mm !important;
                        font-family: Arial, sans-serif;
                        font-size: 14px;
                        line-height: 1.4;
                    }
                    img {
                        max-width: 100%;
                        height: auto;
                        display: block;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table td, table th {
                        border: 1px solid #dee2e6;
                        padding: 6px;
                        font-size: 13px;
                    }
                    .signature-box {
                        border-top: 1px solid #999;
                        padding-top: 15px;
                        margin-top: 50px;
                        height: 80px;
                    }
                }
            </style>
        </head>
        <body>
            ${printContents}
        </body>
        </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();

        // Wait until all resources (like logo) load before printing
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>

@endpush
