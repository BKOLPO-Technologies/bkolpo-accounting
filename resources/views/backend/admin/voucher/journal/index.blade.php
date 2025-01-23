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
                                    <a href="{{ route('journal-voucher.create') }}" class="btn btn-sm btn-success rounded-0">
                                        <i class="fas fa-plus fa-sm"></i> Add New Journal Voucher 
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Voucher No</th>
                                            <th>Branch Name</th>
                                            <th>Date</th>
                                            <th>Head Of Account Name</th>
                                            <th>DR(৳)</th>
                                            <th>CR(৳)</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vouchers as $index => $voucher) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td> 
                                                <td>{{ $voucher->transaction_code }}</td>
                                                <td>{{ $voucher->branch->name ?? 'N/A' }}</td>
                                                <td>{{ $voucher->transaction_date }}</td>
                                                <td>
                                                    @foreach ($transaction->journals as $journal)
                                                        <div>{{ $journal->category->name }}</div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($transaction->journals as $journal)
                                                        <div>{{ $journal->credit }}</div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach ($transaction->journals as $journal)
                                                        <div>{{ $journal->debit }}</div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($voucher->status == 1)
                                                        <a href="#" class="badge badge-success">
                                                            <span class="badge bg-success">Journal Voucher</span>
                                                        </a>
                                                    @else
                                                        <a href="#" class="badge badge-danger">
                                                            <span class="badge bg-danger">Unknown</span>
                                                        </a>
                                                    @endif
                                                </td>           
                                                <td class="col-2">
                                                    <!-- View Button -->
                                                    <a href="{{ route('voucher-journal.show',$voucher->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('voucher-journal.edit',$voucher->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('voucher-journal.delete',$voucher->id)}}" id="delete" class="btn btn-danger btn-sm">
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
@endsection

@push('js')
<script>
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
