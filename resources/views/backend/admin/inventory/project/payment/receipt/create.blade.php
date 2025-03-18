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
                            <a href="{{ route('project.receipt.payment.index')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('project.receipt.payment.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                {{-- Project --}}
                                <div class="col-md-6 mb-3">
                                    <label for="project_id" class="form-label">Project:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-control select2" name="project_id" id="project_id">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                            @endforeach
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
                                        <input type="number" class="form-control" id="pay_amount" name="pay_amount" placeholder="Enter Pay Amount" required>
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

                                <!-- Bank Account Number (hidden initially) -->
                                <div class="col-md-4 mb-3" id="bank_account_div" style="display:none;">
                                    <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_account_no" placeholder="Enter Bank Account No" id="bank_account_no">
                                    </div>
                                </div>

                                <!-- Cheque Number (hidden initially) -->
                                <div class="col-md-4 mb-3" id="cheque_no_div" style="display:none;">
                                    <label for="cheque_no" class="form-label">Cheque No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        <input type="text" class="form-control" name="cheque_no" placeholder="Enter Cheque No" id="cheque_no">
                                    </div>
                                </div>

                                <!-- Cheque Date (hidden initially) -->
                                <div class="col-md-4 mb-3" id="cheque_date_div" style="display:none;">
                                    <label for="cheque_date" class="form-label">Cheque Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="cheque_date" id="to_date" value="{{ date('Y-m-d') }}" >
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="description">Note</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Enter some note"></textarea>
                                    </div>
                                </div>
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
        $('.select2').select2();
        $('#project_id').change(function () {
            let projectId = $(this).val();

            if (projectId) {
                $.ajax({
                    url: "{{ route('project.get.details') }}",
                    type: "GET",
                    data: { project_id: projectId },
                    success: function (response) {
                        if (response.success) {
                            $('#total_amount').val(response.total_amount);
                            $('#due_amount').val(response.due_amount);
                        } else {
                            toastr.error('Project details not found.');
                        }
                    },
                    error: function () {
                        toastr.error('Error fetching project details.');
                    }
                });
            } else {
                $('#total_amount').val('');
                $('#due_amount').val('');
            }
        });

        // Auto calculate Due Amount on Pay Amount input
        $('#pay_amount').on('input', function () {
            let totalAmount = parseFloat($('#total_amount').val()) || 0;
            let payAmount = parseFloat($(this).val()) || 0;
            let dueAmount = totalAmount - payAmount;

            $('#due_amount').val(dueAmount.toFixed(2));
        });
    });
</script>

 <!-- JS to toggle visibility -->
 <script>
    document.getElementById('payment_method').addEventListener('change', function() {
        var paymentMethod = this.value;
        
        // Hide all additional fields
        document.getElementById('bank_account_div').style.display = 'none';
        document.getElementById('cheque_no_div').style.display = 'none';
        document.getElementById('cheque_date_div').style.display = 'none';

        // Show fields based on selected payment method
        if (paymentMethod === 'bank') {
            document.getElementById('bank_account_div').style.display = 'block';
            document.getElementById('cheque_no_div').style.display = 'block';
            document.getElementById('cheque_date_div').style.display = 'block';
        }
    });
</script>
@endpush

