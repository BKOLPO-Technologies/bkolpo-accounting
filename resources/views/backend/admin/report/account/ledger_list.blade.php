@extends('layouts.admin', ['pageTitle' => 'Ledger List'])

@section('admin')

<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- You can add additional content here -->
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
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('report.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To Report List
                                </a>
                            </div>
                        </div>
                        
                        <!-- Card Body with Ledger Name -->
                        <div class="card-body">
                            <div class="row">
                                @foreach($ledgers as $ledger)
                                    <div class="col-md-3">
                                        <div class="card shadow-lg shadow-sm">
                                            <div class="card-header 
                                                @if($ledger->status == '1') bg-success text-white 
                                                @elseif($ledger->status == '0') bg-danger text-white 
                                                @else bg-primary text-white @endif">
                                                <h5 class="card-title">{{ $ledger->name }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-0">Number of Vouchers: {{ $ledger->journalVoucherDetails->count() }}</p>
                                                <!-- Add more information here if needed -->
                                            </div>
                                            <div class="card-footer d-flex justify-content-between">
                                                <button class="btn btn-danger btn-sm w-50 mr-2" 
                                                        onclick="showPaySlip({{ $ledger->id }})">
                                                    <i class="fa fa-file-alt ml-2"></i> Pay Slip
                                                </button>
                                                <a href="{{ route('report.ledger.single.report', $ledger->id) }}" class="btn btn-info btn-sm w-50">
                                                    <i class="fa fa-file-alt ml-2"></i> View Report
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pay Slip Modal -->
                                <div class="modal fade" id="paySlipModal" tabindex="-1" role="dialog" aria-labelledby="paySlipModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="paySlipModalLabel">Pay Slip Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" class="text-light">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="paySlipContent" class="text-center">
                                                    <p>Loading...</p>
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

    // pay slip
    function showPaySlip(ledgerId) {
        // Show modal
        $('#paySlipModal').modal('show');

        // Load Pay Slip data via AJAX
        $.ajax({
            url: "/admin/report/accounts/ledger/pay-slip/" + ledgerId, // Adjust the route as per your Laravel setup
            type: "GET",
            success: function(response) {
                $("#paySlipContent").html(response);
            },
            error: function() {
                $("#paySlipContent").html("<p class='text-danger'>Error loading Pay Slip details.</p>");
            }
        });
    }
</script>
@endpush
