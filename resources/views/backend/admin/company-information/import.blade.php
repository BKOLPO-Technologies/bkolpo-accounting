@extends('layouts.admin', [$pageTitle => 'All Import'])
@section('admin')
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
                        <li class="breadcrumb-item active"> Settings / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary shadow">
                            <div class="card-header d-flex justify-content-between align-items-center">

                                <h3 class="card-title">All Import</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Ledger Group Import Card -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow">
                                            <div class="card-header bg-success text-white d-flex align-items-center">
                                                <i class="fas fa-layer-group mr-2"></i> <!-- Ledger Group Icon -->
                                                <h3 class="card-title m-0">Ledger Group Import</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column justify-content-center align-items-center text-center">
                                                    <p>Import your Ledger Groups easily.</p>
                                                    <a data-toggle="modal" data-target="#importModal" class="btn btn-info">
                                                        <i class="fas fa-upload mr-1"></i> Import Now
                                                    </a>
                                                </div>
                                                <!-- Import Ledger Group Modal -->
                                                <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success">
                                                                <h5 class="modal-title" id="importModalLabel">Import Ledger Group</h5>
                                                                <div class="d-flex align-items-center">
                                                                    <a href="{{ route('ledger.group.import.format') }}" class="btn btn-warning btn-sm mx-3">
                                                                        <i class="fas fa-download"></i> Download Format
                                                                    </a>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('ledger.group.import') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="file-upload">Choose File</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="file-upload" name="file" required>
                                                                                <label class="custom-file-label" for="file-upload">Choose file</label>
                                                                            </div>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="fas fa-upload"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-upload"></i> Import
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ledger Import Card -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow">
                                            <div class="card-header bg-success text-white d-flex align-items-center">
                                                <i class="fas fa-book mr-2"></i> <!-- Ledger Icon -->
                                                <h3 class="card-title m-0">Ledger Import</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column justify-content-center align-items-center text-center">
                                                    <p>Import your Ledgers with ease.</p>
                                                    <a data-toggle="modal" data-target="#importModal1" class="btn btn-info"><i class="fas fa-upload mr-1"></i> Import Now</a>
                                                </div>
                                                <!-- Import Ledger Modal -->
                                                <div class="modal fade" id="importModal1" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success d-flex justify-content-between align-items-center w-100">
                                                                <h5 class="modal-title" id="importModalLabel">Import Ledger</h5>
                                                                <div class="d-flex align-items-center">
                                                                    <a href="{{ route('ledger.import.format') }}" class="btn btn-warning btn-sm mx-3">
                                                                        <i class="fas fa-download"></i> Download Format
                                                                    </a>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('ledger.import') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="file-upload">Choose File</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="file-upload1" name="file" required>
                                                                                <label class="custom-file-label ledger-label" for="file-upload1">Choose file</label>
                                                                            </div>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="fas fa-upload"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-upload"></i> Import
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Journal Import Card -->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card shadow">
                                            <div class="card-header bg-success text-white d-flex align-items-center">
                                                <i class="fas fa-file-invoice mr-2"></i> <!-- Journal Icon -->
                                                <h3 class="card-title m-0">Journal Import</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column justify-content-center align-items-center text-center">
                                                    <p>Import Journal entries quickly.</p>
                                                    <a data-toggle="modal" data-target="#importModal2" class="btn btn-info"> <i class="fas fa-upload mr-1"></i> Import Now</a>
                                                </div>
                                                <!-- Import Journal Modal -->
                                                <div class="modal fade" id="importModal2" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-success d-flex justify-content-between align-items-center w-100">
                                                                <h5 class="modal-title" id="importModalLabel">Import Journal</h5>
                                                                <div class="d-flex align-items-center">
                                                                    <a href="{{ route('journal-voucher.import.format') }}" class="btn btn-warning btn-sm mx-3">
                                                                        <i class="fas fa-download"></i> Download Format
                                                                    </a>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST" action="{{ route('journal-voucher.import') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="file-upload">Choose File</label>
                                                                        <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="file-upload2" name="file" required>
                                                                                <label class="custom-file-label journal-label" for="file-upload">Choose file</label>
                                                                            </div>
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="fas fa-upload"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-right">
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-upload"></i> Import
                                                                        </button>
                                                                    </div>
                                                                </form>
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
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('js')
<script>
    // ledger group
    $(document).ready(function () {
        $('#file-upload').on('change', function (e) {
            // Get the file name
            var fileName = e.target.files[0].name;
            // Set the label text
            $(this).next('.custom-file-label').html(fileName);
        });
    });

    // ledger
    $(document).ready(function () {
        $('#file-upload1').on('change', function (e) {
            // Get the file name
            var fileName = e.target.files[0].name;
            // Set the label text
            $(this).next('.ledger-label').html(fileName);
        });
    });

    // journal
    $(document).ready(function () {
        $('#file-upload2').on('change', function (e) {
            // Get the file name
            var fileName = e.target.files[0].name;
            // Set the label text
            $(this).next('.journal-label').html(fileName);
        });
    });
</script>
@endpush
