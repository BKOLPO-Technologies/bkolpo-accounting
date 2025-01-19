@extends('layouts.admin', [$pageTitle => 'TA/DA Details'])

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
                <li class="breadcrumb-item active" aria-current="page">HR Management / {{ $pageTitle }}</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Details of TA/DA: {{ $tada->user->name ?? 'N/A' }}</h4>
                                <a href="{{ route('ta-da.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <p><strong>Staff Name:</strong> {{ $tada->user->name ?? 'N/A' }}</p>
                                </div>
                                <!-- Designation -->
                                <div class="col-md-6">
                                    <p><strong>TaDa Type:</strong> {{ $tada->type->name ?? 'N/A' }}</p>
                                </div>
                                <!-- Date -->
                                <div class="col-md-6">
                                    <p><strong>Date:</strong> {{ $tada->date }}</p>
                                </div>
                                <!-- Amount -->
                                <div class="col-md-6">
                                    <p><strong>Total Amount:</strong> {{ number_format($tada->total, 2) }}</p>
                                </div>
                            </div>

                            <!-- TA/DA Details Table -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>TA/DA Details:</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Purpose</th>
                                                <th>Amount</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tada->details as $key => $detail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->purpose }}</td>
                                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                                    <td>{{ $detail->remarks ?? 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No details available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end">Total:</th>
                                                <th>
                                                    {{-- Calculate the total amount --}}
                                                    {{ number_format($tada->details->sum('amount'), 2) }}
                                                </th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Attached Files Table -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Attached Files:</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Preview</th>
                                                <th>File Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tada->files as $key => $file)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                            <img src="{{ asset('storage/'.$file->file_path) }}" alt="File Image" width="100">
                                                        @else
                                                            <p>No Preview Available</p>
                                                        @endif
                                                    </td>
                                                    <td>{{ basename($file->file_path) }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/'.$file->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No files attached.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
