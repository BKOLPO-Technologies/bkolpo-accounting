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

                                <!-- Sales Reference No -->
                                <div class="col-md-6 mb-3">
                                    <label for="invoice_no" class="form-label">Reference No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                        <select class="form-control select2" name="invoice_no" id="invoice_no">
                                            <option value="">Select Reference No</option>
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

                                <!-- Total Due Amount (Display) -->
                                <div class="col-md-6 mb-3">
                                    <label for="total_due_amount" class="form-label">Total Due Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="text" name="total_due_amount" class="form-control" id="total_due_amount" readonly>
                                    </div>
                                </div>

                                <!-- Pay Amount -->
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Receive Amount:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="number" class="form-control" id="pay_amount" name="pay_amount" step="0.01" placeholder="Enter Receive Amount" required>
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

                                <!-- Receive Method -->
                                <div class="col-md-6 mb-3">
                                    <label for="ledger_group_id" class="form-label">Select Receive Method:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select class="form-control" name="payment_method" id="payment_method" required>
                                            <option value="">Choose Receive Method</option>
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->id }}" data-type="{{ $ledger->type }}">{{ $ledger->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Receive Mood -->
                                <div class="col-md-6 mb-3">
                                    <label for="ledger_group_id" class="form-label">Select Receive Mood:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        <select class="form-control" name="payment_mood" id="payment_mood">
                                            <option value="">Choose Receive Mood</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cheque">Cheque Payment</option>
                                            <option value="bkash">Bkash</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Bank Account Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="bank_account_div" style="display:none;">
                                    <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_account_no" placeholder="Enter Bank Account No" id="bank_account_no">
                                    </div>
                                </div>

                               <!-- Batch Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="bank_batch_div" style="display:none;">
                                    <label for="bank_batch_no" class="form-label">Batch No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_batch_no" placeholder="Enter Batch No" id="bank_batch_no">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3" id="bank_date_div" style="display:none;">
                                    <label for="bank_date" class="form-label">Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="bank_date" id="bksh_date">
                                    </div>
                                </div>


                                <!-- Cheque Number (hidden initially) -->
                                <div class="col-md-6 mb-3" id="cheque_no_div" style="display:none;">
                                    <label for="cheque_no" class="form-label">Cheque No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        <input type="text" class="form-control" name="cheque_no" placeholder="Enter Cheque No" id="cheque_no">
                                    </div>
                                </div>

                                <!-- Cheque Date (hidden initially) -->
                                <div class="col-md-6 mb-3" id="cheque_date_div" style="display:none;">
                                    <label for="cheque_date" class="form-label">Cheque Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="cheque_date" id="to_date">
                                    </div>
                                </div>

                                <!-- Bkash Number -->
                                <div class="col-md-6 mb-3" id="bkash_number_div" style="display:none;">
                                    <label for="bkash_number" class="form-label">Bkash Number:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                        <input type="text" class="form-control" name="bkash_number" placeholder="Enter Bkash Number" id="bkash_number">
                                    </div>
                                </div>

                                <!-- Reference Number -->
                                <div class="col-md-6 mb-3" id="reference_no_div" style="display:none;">
                                    <label for="reference_no" class="form-label">Reference Number:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        <input type="text" class="form-control" name="reference_no" placeholder="Enter Reference Number" id="reference_no">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3" id="bkash_date_div" style="display:none;">
                                    <label for="bkash_date_div" class="form-label">Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="bkash_date" id="from_date">
                                    </div>
                                </div>

                                <!-- Receive Date -->
                                <div class="col-md-6 mb-3">
                                    <label for="payment_date" class="form-label">Receive Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" id="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>

                                 <!-- Bank Account Number (hidden initially) -->
                                 {{-- <div class="col-md-4 mb-3" id="bank_account_div" style="display:none;">
                                    <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        <input type="text" class="form-control" name="bank_account_no" placeholder="Enter Bank Account No" id="bank_account_no">
                                    </div>
                                </div> --}}

                                <!-- Cheque Number (hidden initially) -->
                                {{-- <div class="col-md-4 mb-3" id="cheque_no_div" style="display:none;">
                                    <label for="cheque_no" class="form-label">Cheque No:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check"></i></span>
                                        <input type="text" class="form-control" name="cheque_no" placeholder="Enter Cheque No" id="cheque_no">
                                    </div>
                                </div> --}}

                                <!-- Cheque Date (hidden initially) -->
                                {{-- <div class="col-md-4 mb-3" id="cheque_date_div" style="display:none;">
                                    <label for="cheque_date" class="form-label">Cheque Date:</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="text" class="form-control" name="cheque_date" id="to_date">
                                    </div>
                                </div> --}}

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

        // Reset Fields
        $('#invoice_no').html('<option value="">Select Reference No</option>');
        $('#total_amount').val('');
        $('#pay_amount').val('');
        $('#due_amount').val('');
        $('#total_due_amount').val('');

        if (projectId) {
            $('#invoice_no').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('project.get.details') }}",
                type: "GET",
                data: { project_id: projectId },
                success: function (response) {
                    // console.log(response);
                    if (response.success) {
                        let options = '<option value="">Select Reference No</option>';
                        response.sales.forEach(sale => {
                            options += `<option value="${sale.invoice_no}" data-total="${sale.grand_total}" data-due="${sale.grand_total - sale.paid_amount}">${sale.invoice_no}</option>`;
                        });

                        $('#invoice_no').html(options);
                    } else {
                        toastr.error('No invoices found for this project.');
                    }
                },
                error: function () {
                    toastr.error('Error fetching project details.');
                }
            });
        }
    });

    // When selecting an invoice, update total_amount and due_amount
    $('#invoice_no').change(function () {
        let selectedOption = $(this).find(':selected');
        let totalAmount = selectedOption.data('total') || 0;
        let dueAmount = selectedOption.data('due') || 0;

        $('#total_amount').val(totalAmount);
        $('#total_due_amount').val(dueAmount);
    });

    // Auto calculate Due Amount on Pay Amount input
    $('#pay_amount').on('input', function () {
        let totalAmount = parseFloat($('#total_due_amount').val()) || 0;
        let payAmount = parseFloat($(this).val()) || 0;

        if (payAmount > totalAmount) {
            toastr.error('Pay amount cannot exceed the due amount!', {
                closeButton: true,
                progressBar: true,
                timeOut: 5000
            });
            $(this).val(totalAmount);
            $('#due_amount').val("0.00");
            return;
        }

        let dueAmount = totalAmount - payAmount;

        $('#due_amount').val(dueAmount.toFixed(2));
    });
});

</script>

 <!-- JS to toggle visibility -->
 <script>
    // document.addEventListener("DOMContentLoaded", function () {
    //     const paymentMethodSelect = document.getElementById('payment_method');
    //     const bankAccountDiv = document.getElementById('bank_account_div');
    //     const chequeNoDiv = document.getElementById('cheque_no_div');
    //     const chequeDateDiv = document.getElementById('cheque_date_div');

    //     paymentMethodSelect.addEventListener('change', function () {
    //         let selectedOption = this.options[this.selectedIndex];
    //         let paymentType = selectedOption.getAttribute('data-type');

    //         if (paymentType === 'Bank') {
    //             bankAccountDiv.style.display = 'block';
    //             chequeNoDiv.style.display = 'block';
    //             chequeDateDiv.style.display = 'block';
    //         } else {
    //             bankAccountDiv.style.display = 'none';
    //             chequeNoDiv.style.display = 'none';
    //             chequeDateDiv.style.display = 'none';
    //         }
    //     });
    // });


    document.addEventListener("DOMContentLoaded", function () {
        const paymentMethodSelect = document.getElementById('payment_method');
        const paymentMoodSelect = document.getElementById('payment_mood');

        const bankAccountDiv = document.getElementById('bank_account_div');
        const bankBatchDiv = document.getElementById('bank_batch_div');
        const chequeNoDiv = document.getElementById('cheque_no_div');
        const chequeDateDiv = document.getElementById('cheque_date_div');
        const bankDateDiv = document.getElementById('bank_date_div');
        const bkashNumberDiv = document.getElementById('bkash_number_div');
        const referenceNoDiv = document.getElementById('reference_no_div');
        const bkashDateDiv = document.getElementById('bkash_date_div');

        // Hide payment mood initially
        paymentMoodSelect.closest('.mb-3').style.display = 'none';

        // Handle Payment Method Change
        paymentMethodSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const paymentType = selectedOption.getAttribute('data-type');

            if (paymentType === 'Bank') {
                paymentMoodSelect.closest('.mb-3').style.display = 'block';
            } else {
                paymentMoodSelect.closest('.mb-3').style.display = 'none';
                hideAllPaymentMoodFields();
            }
        });

        // Handle Payment Mood Change
        paymentMoodSelect.addEventListener('change', function () {
            hideAllPaymentMoodFields();

            const mood = this.value;
            switch (mood) {
                case 'bank_transfer':
                    bankAccountDiv.style.display = 'block';
                    bankBatchDiv.style.display = 'block';
                    bankDateDiv.style.display = 'block';
                    break;
                case 'cheque':
                    bankAccountDiv.style.display = 'block';
                    chequeNoDiv.style.display = 'block';
                    chequeDateDiv.style.display = 'block';
                    break;
                case 'bkash':
                    bkashNumberDiv.style.display = 'block';
                    referenceNoDiv.style.display = 'block';
                    bkashDateDiv.style.display = 'block';
                    break;
            }
        });

        // Function to Hide All Optional Fields
        function hideAllPaymentMoodFields() {
            bankAccountDiv.style.display = 'none';
            bankBatchDiv.style.display = 'none';
            chequeNoDiv.style.display = 'none';
            chequeDateDiv.style.display = 'none';
            bankDateDiv.style.display = 'none';
            bkashDateDiv.style.display = 'none';
            bkashNumberDiv.style.display = 'none';
            referenceNoDiv.style.display = 'none';
        }
    });
</script>
@endpush

