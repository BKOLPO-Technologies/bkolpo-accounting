@extends('layouts.admin', ['pageTitle' => 'Purchases Report'])

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
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                            </div>
                        </div>
                        <div class="card-body mt-3">
                            <!-- Date Filter Form -->
                            <form action="{{ route('report.purchases') }}" method="GET" class="mb-3">
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
                                        <a href="{{ route('report.purchases') }}"  class="btn btn-danger w-100">Clear</a>
                                    </div>
                                </div>
                            </form>

                            <!-- Trial Balance Table -->
                            <div class="card-header text-center mb-3">
                                <h2 class="mb-1">
                                    <img 
                                        src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                                        alt="Company Logo" 
                                        style="height: 40px; vertical-align: middle; margin-right: 10px;"
                                    >
                                    {{ get_company()->name ?? '' }}
                                </h2>
                                <p class="mb-0"><strong>Purchases Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div clas="card-body">
                                <div class="row mb-5">
                                    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                        <!-- Trial Balance Table -->
                                        <div class="table-responsive">
                                            <table id="example3" class="table-striped table-bordered" style=" width: 100%;">
                                                <thead style="border-bottom: 2px solid black;">
                                                    <tr>
                                                        <th style="width: 5%;">Sl</th>
                                                        <th style="width: 20%;">PO No</th>
                                                        <th style="width: 20%;">Vendor Name</th>
                                                        <th style="width: 20%;">Date</th>
                                                        <th class="text-end" style="width: 5%;">Debit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($purchasesReports as $key => $purchases)
                                                        <tr>
                                                            <td class="col-1">{{ $key+1 }}</td>
                                                            <td>{{ $purchases['invoice_no'] }}</td>
                                                            <td>{{ $purchases['supplier_name'] }}</td>
                                                            <td>{{ $purchases['invoice_date'] }}</td>
                                                            <td class="text-end col-2">{{ bdt() }} {{ number_format($purchases['debit'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="fw-bold">
                                                        <td colspan="4" class="font-weight-bolder text-right"></td>
                                                        <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($purchasesReports->sum('debit'), 2) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <!-- Amount in Words: Bottom Left with margin -->
                                            <div id="amountInWordsPrint" style="margin-top: 10px;">
                                                <strong>Amount in Words:</strong>
                                                <strong class="text-uppercase">{{ convertNumberToWords(number_format($purchasesReports->sum('debit'), 2)) }}</strong>
                                            </div>
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
    // Purchases report 
    $("#example3").DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "buttons": [
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
                    // Add print-specific styles
                    var printStyle = document.createElement('style');
                    printStyle.innerHTML = `
                        @media print {
                            body {
                                margin: 0;
                                padding: 40px 15px 0 15px !important;
                                font-size: 14px;
                            }
                            .table {
                                width: 100% !important;
                                font-size: 16px;
                            }
                            .table th, .table td {
                                padding: 4px !important;
                            }
                            .text-end {
                                text-align: left !important;
                            }
                            .text-uppercase {
                                text-transform: uppercase;
                            }
                            #amountInWordsPrint {
                                margin-top: 15px;
                                font-size: 16px;
                            }
                        }
                    `;
                    win.document.head.appendChild(printStyle);
                    
                    // Center align content for the print view
                    $(win.document.body).css('text-align', 'center');

                    // Make the table full width with borders
                    $(win.document.body).find('table').addClass('table table-bordered').css('width', '100%');

                    // Ensure table headers are centered in the print preview
                    $(win.document.body).find('th').css('text-align', 'center');

                    // Remove the default title from the print preview
                    $(win.document.head).find('title').text('Purchases Report - {{ get_company()->name ?? "" }}');

                    // Append custom Header before the table in print
                    $(win.document.body).prepend(`
                        <div class="text-center mb-2" style="margin-bottom: 10px;">
                            <h2 style="margin: 5px 0; font-size: 18px;">
                                <img 
                                    src="{{ !empty(get_company()->logo) ? url('upload/backend/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                                    alt="Company Logo" 
                                    style="height: 30px; vertical-align: middle; margin-right: 10px;"
                                >
                                {{ get_company()->name ?? '' }}
                            </h2>
                            <p style="margin: 3px 0; font-weight: bold;">Purchases Report</p>
                            <p style="margin: 3px 0;">Date: {{ now()->format('d M, Y') }}</p>
                            <p style="margin: 3px 0;">Period: {{ request('from_date', $fromDate) }} to {{ request('to_date', $toDate) }}</p>
                        </div>
                    `);

                    // Ensure the table footer (tfoot) is shown in print view
                    var tfoot = $('tfoot').clone(); // Clone the existing footer
                    $(win.document.body).find('table').append(tfoot); // Append it to the printed table

                    // Adjust the footer for the print view
                    $(win.document.body).find('tfoot tr').addClass('fw-bold');

                    // Custom column width for print view
                    $(win.document.body).find('th, td').each(function() {
                        if ($(this).text().trim() === 'Sl') {
                            $(this).css('width', '5%'); // Smaller Sl column
                        }
                        if ($(this).text().trim() === 'Ledger Name') {
                            $(this).css('width', '30%'); // Larger Ledger Name column
                        }
                        if ($(this).text().includes('Debit') || $(this).text().includes('Credit')) {
                            $(this).css('width', '20%'); // Smaller Debit and Credit columns
                        }
                    });

                    // Ensure footer is displayed correctly at the bottom
                    $(win.document.body).find('tfoot').css({
                        "position": "relative",
                        "width": "100%",
                        "text-align": "center",
                        "font-weight": "bold",
                        "border-top": "2px solid black"
                    });

                    // Clone and append the "Amount in Words" section
                    var amountInWordsHtml = $('#amountInWordsPrint').clone();
                    $(win.document.body).append(amountInWordsHtml);

                    // Style the amount section for print
                    $(win.document.body).find('#amountInWordsPrint').css({
                        "margin-top": "15px",
                        "font-size": "16px",
                        "font-weight": "bold",
                        "text-align": "left",
                        "page-break-inside": "avoid"
                    });
                    
                    // Add page break avoidance to prevent splitting content
                    $(win.document.body).find('table').css('page-break-inside', 'avoid');
                }
            },
        ],
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
</script>
@endpush