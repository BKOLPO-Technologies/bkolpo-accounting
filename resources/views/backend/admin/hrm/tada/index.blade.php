@extends('layouts.admin', [$pageTitle => 'TA/DA List'])

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
                <div class="col-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle }}</h4>
                                <a href="{{ route('ta-da.create') }}" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-plus"></i> Add TA/DA
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Staff Name</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tads as $key => $ta)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $ta->user->name ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ta->date)->format('d F Y') }}</td>
                                                <td>{{ number_format($ta->total, 2) }}</td>
                                                <td>
                                                    <!-- View Button -->
                                                    <a href="{{ route('ta-da.show',$ta->id)}}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="{{ route('ta-da.edit',$ta->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <a href="{{ route('ta-da.delete',$ta->id)}}" id="delete" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No records found.</td>
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
    </section>
</div>
@endsection
