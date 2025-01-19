@extends('layouts.admin', [$pageTitle => 'Edit TA/DA'])
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
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('ta-da.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back to List TA/DA
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('ta-da.update', $taDa->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="subtotal" id="hiddenSubtotal">
                            <input type="hidden" name="total" id="hiddenTotal">
                            <div class="card-body">
                                <!-- Form Fields -->
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="staff_id">Select Staff</label>
                                            <select name="staff_id" id="staff_id" class="form-control select2" required>
                                                <option value="">Select Staff</option>
                                                @foreach($staffs as $staff)
                                                    <option value="{{ $staff->id }}" {{ $taDa->user_id == $staff->id ? 'selected' : '' }}>{{ $staff->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('staff_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="tada_type">TaDa Type</label>
                                            <select name="tada_type" id="tada_type" class="form-control select2" required>
                                                <option value="">Select TaDa Type</option>
                                                @foreach($tadaTypes as $tadaType)
                                                    <option value="{{ $tadaType->id }}"  {{ $taDa->tada_type_id == $tadaType->id ? 'selected' : '' }}>{{ $tadaType->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('tada_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="date">Date:</label>
                                            <input type="date" class="form-control" name="date" value="{{ $taDa->date }}" placeholder="Enter Date">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea name="description" class="form-control" id="description" cols="5" rows="5" placeholder="Enter description">{{ $taDa->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add/Remove Rows -->
                                <div class="row mb-2">
                                    <div class="col-lg-12 text-end">
                                        <button type="button" class="btn btn-success addRow"><i class="fas fa-plus"></i> Add Row</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table" id="detailsTable">
                                        <thead>
                                            <tr>
                                                <th>Purpose Name</th>
                                                <th>Amount</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($taDa->details as $detail)
                                                <tr>
                                                    <td><input type="text" class="form-control" name="purpose[]" value="{{ $detail->purpose }}"></td>
                                                    <td><input type="number" class="form-control amount" name="amount[]" value="{{ $detail->amount }}"></td>
                                                    <td><textarea class="form-control" name="remarks[]">{{ $detail->remarks }}</textarea></td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-minus"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Summary -->
                                <div class="row">
                                    <div class="col-6 offset-3">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:50%">Subtotal:</th>
                                                        <td id="subtotal">{{ number_format($taDa->subtotal, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td id="total">{{ number_format($taDa->total, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                                <!-- Submit -->
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('js')
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#detailsTable tbody');
    const updateTotals = () => {
        const amounts = document.querySelectorAll('.amount');
        let subtotal = 0;
        amounts.forEach(amount => {
            subtotal += parseFloat(amount.value || 0); // Ensure no NaN values
        });
        document.getElementById('subtotal').textContent = subtotal.toFixed(2); // Update subtotal display
        document.getElementById('total').textContent = subtotal.toFixed(2); // Update total display
        document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2); // Update hidden input for subtotal
        document.getElementById('hiddenTotal').value = subtotal.toFixed(2); // Update hidden input for total
    };

    // Add new row functionality
    document.querySelector('.addRow').addEventListener('click', () => {
        const row = `
            <tr>
                <td><input type="text" class="form-control" name="purpose[]" placeholder="Purpose"></td>
                <td><input type="number" class="form-control amount" name="amount[]" placeholder="Amount"></td>
                <td><textarea class="form-control" name="remarks[]" placeholder="Remarks"></textarea></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-minus"></i></button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });

    // Remove row functionality
    tableBody.addEventListener('click', (e) => {
        if (e.target.closest('.removeRow')) {
            e.target.closest('tr').remove();
            updateTotals(); // Recalculate totals after removing a row
        }
    });

    // Update totals when the input fields change
    tableBody.addEventListener('input', updateTotals);

    // Initial calculation for any pre-existing amounts
    updateTotals();
    });

</script>
@endpush
