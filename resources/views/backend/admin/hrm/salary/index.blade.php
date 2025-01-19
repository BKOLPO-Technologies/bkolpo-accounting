@extends('layouts.admin', ['pageTitle' => 'Salary List'])

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
            <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
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
                <a href="{{ route('salary.create') }}" class="btn btn-sm btn-success rounded-0">
                  <i class="fas fa-plus fa-sm"></i> Add Salary
                </a>
              </div>
            </div>
            <div class="card-body">
              <!-- Search Form -->
              <form action="{{ route('salary.index') }}" method="GET">
                <div class="row mb-3 justify-content-center">
                  <div class="col-md-3">
                    <select name="month" class="form-control" required>
                      <option value="">Select Month</option>
                      @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ request()->month == $month ? 'selected' : '' }}>
                          {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select name="year" class="form-control" required>
                      <option value="">Select Year</option>
                      @foreach(range(2020, date('Y')) as $year)
                        <option value="{{ $year }}" {{ request()->year == $year ? 'selected' : '' }}>{{ $year }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="submit" class="btn btn-danger">Reload</button>
                  </div>
                </div>
              </form>

              <!-- Salary Table -->
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Salary</th>
                    <th>Payment</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($salarys as $key => $salary)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        <img src="{{ (!empty($salary->staff->profile_image)) ? url('upload/staff/'.$salary->staff->profile_image):url('https://via.placeholder.com/70x60') }}" class="staff-profile-image-small" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%;">
                      </td>
                      <td>{{ $salary->staff->name ?? 'N/A' }}</td>
                      <td class="font-weight-bolder">৳{{ number_format($salary->salary, 2) }}</td>
                      <td class="font-weight-bolder">৳{{ number_format($salary->payment_amount, 2) }}</td>
                      <td class="font-weight-bolder">৳{{ number_format($salary->will_get, 2) }}</td>
                      <td>
                        @if ($salary->status == 'Paid')
                          <span class="p-1 bg-success text-white rounded">Paid</span>
                        @elseif ($salary->status == 'Partially Paid')
                          <span class="p-1 bg-warning text-white rounded">Partially Paid</span>
                        @elseif ($salary->status == 'Unpaid')
                          <span class="p-1 bg-danger text-white rounded">Not Paid</span>
                        @else
                          <span class="p-1 bg-secondary text-white rounded">Undefined</span>
                        @endif
                      </td>
                      <td>
                        <a href="{{ route('salary.show',$salary->id) }}" class="btn btn-success btn-sm">
                          <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#paymentModal{{$salary->id }}">
                          <i class="fas fa-dollar-sign"></i> Pay
                        </button>
                        <a href="{{ route('salary.delete',$salary->id) }}" id="delete" class="btn btn-danger btn-sm">
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>

                    <!-- Payment Modal -->
                    <div class="modal fade" id="paymentModal{{$salary->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <form action="{{ route('salary.updatePaymentStatus',$salary->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Salary Payment</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="salary_id" id="salary_id">
                              <div class="form-group">
                                <label for="payment_amount">Payment Amount</label>
                                <input type="number" class="form-control" name="payment_amount" id="payment_amount" required>
                              </div>
                              <div class="form-group">
                                <label for="payment_mode">Payment Mode</label>
                                <select class="form-control" name="payment_mode" id="payment_mode">
                                  <option value="Bank">Bank</option>
                                  <option value="Cash">Cash</option>
                                  <option value="Cheque">Cheque</option>
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" class="btn btn-success">Save Payment</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">No records found.</td>
                    </tr>
                  @endforelse
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
    $(document).ready(function() {
        $('form').on('submit', function() {
            // Change button text to "Reloading..." when form is being submitted
            $('button[type="submit"]').text('Reloading...').prop('disabled', true);
        });
    });
</script>
@endpush