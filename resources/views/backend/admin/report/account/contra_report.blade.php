@extends('layouts.admin', ['pageTitle' => 'Report List'])
@section('admin')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* Optional: hide the print button itself when printing */
            #printableArea .btn {
                display: none !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? '' }}</li>
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
                            <div class="card-header py-2 text-center">
                                <h4 class="mb-0">{{ $pageTitle ?? '' }}</h4>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::now()->format('F d, Y') }}
                                </small>
                            </div>

                            <div class="card-body">
                                
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
