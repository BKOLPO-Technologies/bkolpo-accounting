@extends('layouts.admin', ['pageTitle' => 'Journal Voucher Create'])

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
                                    <a href="{{ route('journal-voucher.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                              <form method="POST" action="{{ route('journal-voucher.store') }}" enctype="multipart/form-data">
                                  @csrf
                                  <div class="row">
                                      <!-- Transaction Code (Auto Generated) -->
                                      <div class="col-lg-4 mb-3">
                                          <label for="transaction_code">Transaction Code</label>
                                          <input type="text" id="transaction_code" name="transaction_code" class="form-control" value="{{ $transactionCode }}" readonly />
                                      </div>

                                      <!-- Company Select -->
                                      <div class="col-lg-4 mb-3">
                                          <label for="branch_id">Company</label>
                                          <select name="branch_id" id="branch_id" class="form-control select2">
                                              @foreach($branches as $branch)
                                                  <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                              @endforeach
                                          </select>
                                      </div>

                                      <div class="col-lg-4 mb-3">
                                          <label for="branch_id">Branch</label>
                                          <select name="branch_id" id="branch_id" class="form-control select2">
                                              @foreach($branches as $branch)
                                                  <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>

                                  <div class="row">
                                      <!-- Date Input -->
                                      <div class="col-lg-12 mb-3">
                                          <label for="transaction_date">Transaction Date</label>
                                          <input type="date" id="transaction_date" name="transaction_date" class="form-control" required />
                                      </div>

                                      <!-- Description Input -->
                                      <div class="col-lg-12 mb-3">
                                          <label for="description">Description</label>
                                          <textarea name="description" id="description" class="form-control" placeholder="Enter Description" rows="2"></textarea>
                                      </div>
                                  </div>

                                  <!-- Debit and Credit Table with 10 Default Rows -->
                                  <table class="table table-bordered mt-4" id="journal-table">
                                      <thead>
                                          <tr>
                                              <th>Ledger</th>
                                              <th>Debit Amount</th>
                                              <th>Credit Amount</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                            <td>
                                                <select name="ledger_id[]" class="form-control select2">
                                                    @foreach($ledgers as $ledger)
                                                        <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                                                    @endforeach
                                                </select>
                                              </td>
                                              <td>
                                                  <input type="number" name="debit[]" class="form-control" step="0.01" value="0.00" />
                                              </td>
                                              <td>
                                                  <input type="number" name="credit[]" class="form-control" step="0.01" value="0.00" />
                                              </td>
                                              <td>
                                                  <button type="button" class="btn btn-success add-row"><i class="fas fa-plus"></i></button>
                                              </td>
                                          </tr>
                                      </tbody>
                                  </table>

                                  <div class="row mt-3">
                                      <div class="col-lg-12">
                                          <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                              <i class="fas fa-save"></i> Save Journal Voucher
                                          </button>
                                      </div>
                                  </div> 
                              </form>
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
   $(document).ready(function() {
        // Initialize Select2 for existing rows
        $('.select2').select2();

        // Handle adding a new row dynamically
        $(document).on('click', '.add-row', function() {
            var newRow = `
                <tr>
                    <td>
                        <select name="ledger_id[]" class="form-control select2">
                            @foreach($ledgers as $ledger)
                                <option value="{{ $ledger->id }}">{{ $ledger->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="debit[]" class="form-control" step="0.01" value="0.00" />
                    </td>
                    <td>
                        <input type="number" name="credit[]" class="form-control" step="0.01" value="0.00" />
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row"><i class="fas fa-minus"></i></button>
                    </td>
                </tr>
            `;
            // Append the new row to the table body
            $('#journal-table tbody').append(newRow);
            // Reinitialize Select2 for the new row
            $('.select2').select2();
        });

        // Handle removing a row
        $(document).on('click', '.remove-row', function() {
            // Remove the row from the table
            $(this).closest('tr').remove();
        });
    });
</script>
@endpush
