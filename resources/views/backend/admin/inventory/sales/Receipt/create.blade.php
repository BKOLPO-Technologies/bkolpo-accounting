@extends('layouts.admin', ['pageTitle' => 'Receive Payment'])
@section('admin')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                            <a href="{{ route('receipt.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('receipt.payment.store') }}" method="POST">
                            @csrf
                            <div class="row">
                               
                                <!-- Client Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="supplier_id" class="form-label">Supplier:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-control select2" name="supplier_id" id="supplier_id">
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Outcoming Chalan -->
                                <div class="col-md-6 mb-3">
                                    <label for="outcoming_chalan_id" class="form-label">Outcoming Chalan:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                        <select class="form-control select2" name="outcoming_chalan_id" id="outcoming_chalan_id">
                                            <option value="">Select Chalan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Total Amount (Display) -->
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="text" name="total_amount" class="form-control" id="total_amount" readonly>
                                    </div>
                                </div>

                                <!-- Pay Amount -->
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Pay Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" class="form-control" id="pay_amount" name="pay_amount"  step="0.01" required>
                                    </div>
                                </div>

                                <!-- Due Amount (Automatically Calculated) -->
                                <div class="col-md-6 mb-3">
                                    <label for="due_amount" class="form-label">Due Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-exclamation-circle"></i></span>
                                        <input type="number" class="form-control" id="due_amount" name="due_amount" step="0.01" readonly>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="ledger_group_id" class="form-label">Select Payment Method:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select class="form-control" name="payment_method" id="payment_method" required>
                                            <option value="">Choose Payment Method</option>
                                            <option value="cash" {{ old('payment_method', $payment->payment_method ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank" {{ old('payment_method', $payment->payment_method ?? '') === 'bank' ? 'selected' : '' }}>Bank</option>
                                        </select>
                                    </div>
                                </div>


                                <!-- Payment Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date" class="form-label">Payment Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" id="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Description</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter the description"></textarea>
                                    </div>
                                </div> --}}
                                
                            </div>
                            <!-- Submit Button (Right-Aligned) -->
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Submit Payment
                                </button>
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
   $(document).ready(function () {
        // When customer changes, reset fields
        $('#supplier_id').on('change', function () {
            let supplierId = $(this).val();
            
            // Clear Out Coming Chalan and Total Amount
            $('#outcoming_chalan_id').html('<option value="">Select Chalan</option>');
            $('#total_amount').val(''); // Clear total amount field
            $('#pay_amount').val('');
            $('#due_amount').val('');

            // When chalan is selected, update total amount
            if (supplierId) {
                $('#outcoming_chalan_id').html('<option value="">Loading...</option>'); // Show loading state
                
                let totalAmount = $(this).find(':selected').data('amount') || 0;
                $('#total_amount').val(totalAmount);
                $('#pay_amount').val(''); // Reset pay amount
                $('#due_amount').val(totalAmount); // Default due = total at first

                $.ajax({
                    url: "{{ route('receipt.payment.get.chalans.by.supplier') }}", // Make sure this route exists
                    type: "GET",
                    data: { supplier_id: supplierId },
                    success: function (response) {
                        let options = '<option value="">Select Chalan</option>';
                        response.chalans.forEach(chalan => {
                            options += `<option value="${chalan.id}" data-amount="${chalan.total_amount}">${chalan.invoice_no}</option>`;
                        });
                        $('#outcoming_chalan_id').html(options);
                    }
                });
            }
        });

        // Show Total Amount when Chalan is selected
        $('#outcoming_chalan_id').on('change', function () {
            let totalAmount = $(this).find(':selected').data('amount') || '';
            $('#total_amount').val(totalAmount);
        });
    });

    // When pay amount is entered, calculate due amount
    $('#pay_amount').on('keyup change', function () {
        let totalAmount = parseFloat($('#total_amount').val()) || 0;
        let payAmount = parseFloat($(this).val()) || 0;

        if (payAmount > totalAmount) {
            toastr.error('Pay amount cannot be greater than Total Amount!');
            $(this).val(totalAmount); // Reset pay amount to total amount
            payAmount = totalAmount; // Prevent further calculation issues
        }

        let dueAmount = totalAmount - payAmount;
        $('#due_amount').val(dueAmount.toFixed(2));
    });

    
    
    // ledger group wise change
    $(document).ready(function() {
        $('.select2').select2();

        $('#ledger_group_id').on('change', function() {
            let groupId = $(this).val();
            $('#ledger_id').html('<option value="">Loading...</option>');

            if (groupId) {
                $.ajax({
                    url: "{{ route('sale.payment.get.ledgers.by.group') }}",
                    type: "GET",
                    data: { ledger_group_id: groupId },
                    success: function(response) {
                        let options = '<option value="">Choose Ledger</option>';
                        response.ledgers.forEach(ledger => {
                            options += `<option value="${ledger.id}">${ledger.name}</option>`;
                        });
                        $('#ledger_id').html(options);
                    }
                });
            } else {
                $('#ledger_id').html('<option value="">Choose Ledger</option>');
            }
        });
    });
</script>
@endpush
