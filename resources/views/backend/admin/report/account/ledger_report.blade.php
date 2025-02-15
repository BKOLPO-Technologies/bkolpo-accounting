@extends('layouts.admin', ['pageTitle' => 'Ledger Report'])

@section('admin')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
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
                                <h4 class="mb-0">Ledger Name - {{ $ledger->name }} </h4>
                                <a href="{{ route('report.ledger.report')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body mt-3">
                            <!-- Date Filter Form -->
                            <form action="{{ route('report.ledger.single.report',$ledger->id) }}" method="GET" class="mb-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-3 mt-3">
                                        <label for="from_date">From Date:</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label for="to_date">To Date:</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                    </div>
                                    <div class="col-md-1 mt-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                    <div class="col-md-1 mt-3 d-flex align-items-end">
                                        <a href="{{ route('report.ledger.single.report',$ledger->id) }}"  class="btn btn-danger w-100">Clear</a>
                                    </div>
                                </div>
                            </form>

                            <!-- Ledger Balance Table -->
                            <div class="card-header text-center mb-3">
                                <h2 class="mb-1">{{ config('app.name') }}</h2> 
                                <p class="mb-0"><strong>Ledger Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div clas="card-body">
                                <div class="row mb-5">
                                    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                        <!-- Ledger Balance Table -->
                                        <div class="table-responsive">
                                            <table id="example5" class="table-striped table-bordered" style=" width: 100%;">
                                                <thead style="border-bottom: 2px solid black; text-align:center;">
                                                    <tr>
                                                        <th style="width: 5%;">Sl</th>
                                                        <th>Reference No</th>
                                                        <th>Description</th>
                                                        <th>Date</th>
                                                        <th class="text-end" style="width: 5%;">Debit (৳)</th>
                                                        <th class="text-end" style="width: 5%;">Credit (৳)</th>
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
                                                            <td class="col-1">{{  $loop->iteration }}</td>
                                                            <td>{{ $voucherDetail->reference_no }}</td>
                                                            <td>{{ $voucherDetail->description }}</td>
                                                            <td>{{ date('d M, Y', strtotime($voucherDetail->journalVoucher->transaction_date)) }}</td>
                                                            <td class="text-end col-2">৳{{ number_format($voucherDetail->debit, 2) }}</td>
                                                            <td class="text-end col-2">৳{{ number_format($voucherDetail->credit, 2) }}</td>
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
                                    </div>
                                </div>
                            </div>
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
    $(document).ready(function () {
        $('#example5').DataTable({
            "paging": false,                     // Enable pagination
            "lengthChange": true,               // Show "Show Entries" dropdown
            "lengthMenu": [ [5, 10, 25, 50], [5, 10, 25, 50] ],  // Dropdown options for number of entries
            "searching": false,                  // Enable search bar
            "ordering": false,                   // Enable sorting
            "info": false,                       // Show info about records
            "autoWidth": false,                 // Disable auto width
            "responsive": true,                 // Make the table responsive
            "pagingType": "simple_numbers",     // Show only Next/Previous buttons
            dom: 'Bfrtip',                      // Add the buttons to the DOM
            buttons: [
                {
                    extend: "excel",
                    className: "btn btn-info",
                    text: '<i class="fa fa-file-excel"></i> Excel'
                },
                {
                    extend: "print",
                    className: "btn btn-warning",
                    text: '<i class="fa fa-print"></i> Print',
                    title: '', // Remove the default page title
                    exportOptions: {
                        columns: ':visible', // Ensures all visible columns are printed
                        modifier: {
                            page: 'all'
                        }
                    },
                    customize: function (win) {
                        // Center align content for the print view
                        $(win.document.body).css('text-align', 'center');

                        // Make the table full width with borders
                        $(win.document.body).find('table').addClass('table table-bordered').css('width', '100%');

                        // Ensure table headers are centered in the print preview
                        $(win.document.body).find('th').css('text-align', 'center');

                        // Remove the default title from the print preview
                        $(win.document.head).find('title').remove();

                        // Append custom Trial Balance Header before the table in print
                        $(win.document.body).prepend(`
                            <div class="text-center mb-3">
                                <h2 class="mb-1">{{ config('app.name') }}</h2>
                                <p class="mb-0"><strong>Ledger Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div class="d-flex justify-content-start mb-3">
                                <h3 class="mb-0 font-weight-bolder">Ledger Name - {{ $ledger->name }}</h3>
                            </div>
                        `);

                        // Ensure the table footer (tfoot) is shown in print view
                        var tfoot = $('tfoot').clone(); // Clone the existing footer
                        $(win.document.body).find('table').append(tfoot); // Append it to the printed table

                        // Adjust the footer for the print view
                        $(win.document.body).find('tfoot tr').addClass('fw-bold');

                        // Ensure footer is displayed correctly at the bottom
                        $(win.document.body).find('tfoot').css({
                            "position": "relative",
                            "bottom": "0px",
                            "width": "100%",
                            "text-align": "center",
                            "font-weight": "bold",
                            "border-top": "2px solid black"
                        });
                    }
                },
            ]
        });
    });
</script>
@endpush

