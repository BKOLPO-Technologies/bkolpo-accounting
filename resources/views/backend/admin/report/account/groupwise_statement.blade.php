@extends('layouts.admin', ['pageTitle' => 'Ledger Gruop Wise Report'])

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
                            <form action="{{ route('report.groupwise.statement') }}" method="GET" class="mb-3">
                                <div class="row justify-content-center">
                                    <div class="col-md-2 mt-3">
                                        <label for="from_date">From Date:</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control" value="{{ request('from_date', $fromDate) }}">
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label for="to_date">To Date:</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', $toDate) }}">
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label for="name">Name:</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ request('name', $nameSearch ?? '') }}" placeholder="Enter name">
                                    </div>
                                    <div class="col-md-1 mt-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                    <div class="col-md-1 mt-3 d-flex align-items-end">
                                        <a href="{{ route('report.groupwise.statement') }}"  class="btn btn-danger w-100">Clear</a>
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
                                <p class="mb-0"><strong>Ledger Group Wise Report</strong></p>
                                <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
                            </div>
                            <div clas="card-body">
                                <div class="row mb-5">
                                    <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
                                        <!-- Group-wise Statement Table -->
                                        <div class="table-responsive">
                                           <table id="example3" class="table-striped table-bordered" style="width: 100%;">
                                                <thead style="border-bottom: 2px solid black;">
                                                    <tr>
                                                        {{-- <th style="width: 5%;">Sl</th> --}}
                                                        <th style="width: 30%;">Group / Ledger Name</th>
                                                        <th class="text-end">Total Debit</th>
                                                        <th class="text-end">Total Credit</th>
                                                        <th class="text-end">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $grandDebit = 0;
                                                        $grandCredit = 0;
                                                    @endphp

                                                    @foreach($groups as $group)
                                                        @php
                                                            $groupDebit = 0;
                                                            $groupCredit = 0;
                                                        @endphp

                                                        <tr style="background-color: #f0f0f0;">
                                                            <td colspan="4"><strong>{{ $group->group_name }}</strong></td>
                                                        </tr>

                                                        @foreach($group->subGroups as $subGroup)
                                                            @php
                                                                $subGroupDebit = 0;
                                                                $subGroupCredit = 0;
                                                            @endphp

                                                            <tr style="background-color: #e9e9e9;">
                                                                <td class="ps-2"><strong>↳ {{ $subGroup->subgroup_name }}</strong></td>
                                                                <td colspan="3"></td>
                                                            </tr>

                                                            @foreach($subGroup->ledgers as $ledger)
                                                                @php
                                                                    $ledgerDebit = $ledger->journalVoucherDetails->sum('debit');
                                                                    $ledgerCredit = $ledger->journalVoucherDetails->sum('credit');
                                                                    $ledgerBalance = $ledgerDebit - $ledgerCredit;

                                                                    $subGroupDebit += $ledgerDebit;
                                                                    $subGroupCredit += $ledgerCredit;
                                                                    $groupDebit += $ledgerDebit;
                                                                    $groupCredit += $ledgerCredit;
                                                                @endphp
                                                                <tr>
                                                                    <td class="ps-4">↳ {{ $ledger->name ?? 'Unnamed Ledger' }}</td>
                                                                    <td class="text-end">{{ number_format($ledgerDebit, 2) }}</td>
                                                                    <td class="text-end">{{ number_format($ledgerCredit, 2) }}</td>
                                                                    <td class="text-end">{{ number_format(abs($ledgerBalance), 2) }} {{ $ledgerBalance >= 0 ? 'Dr' : 'Cr' }}</td>
                                                                </tr>
                                                            @endforeach

                                                            {{-- Subgroup total (optional) --}}
                                                            <tr>
                                                                <td class="text-end"><em>Subgroup Total</em></td>
                                                                <td class="text-end">{{ number_format($subGroupDebit, 2) }}</td>
                                                                <td class="text-end">{{ number_format($subGroupCredit, 2) }}</td>
                                                                <td class="text-end">{{ number_format(abs($subGroupDebit - $subGroupCredit), 2) }} {{ ($subGroupDebit - $subGroupCredit) >= 0 ? 'Dr' : 'Cr' }}</td>
                                                            </tr>
                                                        @endforeach

                                                        @php
                                                            $groupBalance = $groupDebit - $groupCredit;
                                                            $grandDebit += $groupDebit;
                                                            $grandCredit += $groupCredit;
                                                        @endphp

                                                        {{-- Group total row --}}
                                                        <tr style="background-color: #f5f5f5;">
                                                            <td class="text-end"><strong>{{ $group->group_name }} Total</strong></td>
                                                            <td class="text-end"><strong>{{ number_format($groupDebit, 2) }}</strong></td>
                                                            <td class="text-end"><strong>{{ number_format($groupCredit, 2) }}</strong></td>
                                                            <td class="text-end"><strong>{{ number_format(abs($groupBalance), 2) }} {{ $groupBalance >= 0 ? 'Dr' : 'Cr' }}</strong></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                               <tfoot>
                                                    @php
                                                        $grandBalance = $grandDebit - $grandCredit;
                                                    @endphp
                                                    <tr class="fw-bold">
                                                        <td class="text-end">Grand Total</td>
                                                        <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($grandDebit, 2) }}</td>
                                                        <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format($grandCredit, 2) }}</td>
                                                        <td class="text-end font-weight-bolder">{{ bdt() }} {{ number_format(abs($grandBalance), 2) }} {{ $grandBalance >= 0 ? 'Dr' : 'Cr' }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <!-- Amount in Words -->
                                            <div id="amountInWordsPrint" style="margin-top: 10px;">
                                                <strong>Amount in Words:</strong>
                                                <strong class="text-uppercase">{{ convertNumberToWords(number_format(abs($grandBalance), 2)) }} {{ $grandBalance >= 0 ? 'Taka Debit' : 'Taka Credit' }}</strong>
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
    // trial balance report 
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
                            <h2 class="mb-1">{{ get_company()->name ?? '' }}</h2>
                            <p class="mb-0"><strong>Ledger Group Wise Report</strong></p>
                            <p class="mb-0">Date: {{ now()->format('d M, Y') }}</p>
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
                            $(this).css('width', '40%'); // Larger Ledger Name column
                        }
                        if ($(this).text().includes('Debit') || $(this).text().includes('Credit')) {
                            $(this).css('width', '20%'); // Smaller Debit and Credit columns
                        }
                    });

                    // Ensure footer is displayed correctly at the bottom
                    $(win.document.body).find('tfoot').css({
                        "position": "relative",
                        "bottom": "0px",
                        "width": "100%",
                        "text-align": "left",
                        "font-weight": "bold",
                        "border-top": "2px solid black"
                    });

                    // Clone and append the "Amount in Words" section
                    var amountInWordsHtml = $('#amountInWordsPrint').clone();
                    $(win.document.body).append(amountInWordsHtml);

                    // Style the amount section for print
                    $(win.document.body).find('#amountInWordsPrint').css({
                        "margin-top": "20px",
                        "font-size": "16px",
                        "font-weight": "bold",
                        "text-align": "left"
                    });
                }
            },
        ],
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
</script>
@endpush
