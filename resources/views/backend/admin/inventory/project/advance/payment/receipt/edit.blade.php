@extends('layouts.admin', ['pageTitle' => 'Receive Payment'])
@section('admin')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}"
                                    style="text-decoration: none; color: black;">Home</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
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
                                <a href="{{ route('project.advance.receipt.payment.index') }}"
                                    class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('project.advance.receipt.payment.update', $receipt->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    {{-- Project --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="project_id">Project</label>
                                        <div class="input-group">
                                            <select name="project_id" id="project_id"
                                                class="form-control select2 @error('project_id') is-invalid @enderror"
                                                required>
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}"
                                                        data-reference="{{ $project->reference_no }}"
                                                        {{ old('project_id', $receipt->project_id) == $project->id ? 'selected' : '' }}>
                                                        {{ $project->project_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('project_id')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Sales Reference No -->
                                    <div class="col-md-6 mb-3">
                                        <label for="reference_no" class="form-label">Reference No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                            <input type="text" name="reference_no" id="reference_no"
                                                class="form-control @error('reference_no') is-invalid @enderror"
                                                value="{{ old('reference_no', $receipt->reference_no) }}"
                                                placeholder="Enter Reference No" readonly>
                                            @error('reference_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Receive Amount -->
                                    <div class="col-md-6 mb-3">
                                        <label for="receive_amount" class="form-label">Receive Amount:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                            <input type="number" class="form-control" id="receive_amount"
                                                name="receive_amount"
                                                value="{{ old('receive_amount', $receipt->receive_amount) }}"
                                                step="0.01" required>
                                        </div>
                                    </div>

                                    <!-- Receive Method -->
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_method" class="form-label">Select Receive Method:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            <select class="form-control" name="payment_method" id="payment_method" required>
                                                <option value="">Choose Receive Method</option>
                                                @foreach ($ledgers as $ledger)
                                                    <option value="{{ $ledger->id }}" data-type="{{ $ledger->type }}"
                                                        {{ old('payment_method', $receipt->ledger_id) == $ledger->id ? 'selected' : '' }}>
                                                        {{ $ledger->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Receive Mood -->
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_mood" class="form-label">Select Receive Mood:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            <select class="form-control" name="payment_mood" id="payment_mood" >
                                                <option value="">Choose Receive Mood</option>
                                                <option value="bank_transfer"
                                                    {{ old('payment_mood', $receipt->payment_mood) == 'bank_transfer' ? 'selected' : '' }}>
                                                    Bank Transfer</option>
                                                <option value="cheque"
                                                    {{ old('payment_mood', $receipt->payment_mood) == 'cheque' ? 'selected' : '' }}>
                                                    Cheque Payment</option>
                                                <option value="bkash"
                                                    {{ old('payment_mood', $receipt->payment_mood) == 'bkash' ? 'selected' : '' }}>
                                                    Bkash</option>
                                            </select>
                                        </div>
                                    </div>

                                               <!-- Bank Account Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="bank_account_div" style="display:none;">
                                        <label for="bank_account_no" class="form-label">Bank Account No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                            <input type="text" class="form-control" name="bank_account_no"    value="{{ old('bank_account_no', $receipt->bank_account_no) }}"
                                                placeholder="Enter Bank Account No" id="bank_account_no">
                                        </div>
                                    </div>

                                    <!-- Batch Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="bank_batch_div" style="display:none;">
                                        <label for="bank_batch_no" class="form-label">Batch No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                            <input type="text" class="form-control" name="bank_batch_no"  value="{{ old('bank_batch_no', $receipt->bank_batch_no) }}"
                                                placeholder="Enter Batch No" id="bank_batch_no">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="bank_date_div" style="display:none;">
                                        <label for="bank_date" class="form-label">Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="bank_date" value="{{ old('bank_date', $receipt->bank_date) }}" id="bksh_date">
                                        </div>
                                    </div>


                                    <!-- Cheque Number (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="cheque_no_div" style="display:none;">
                                        <label for="cheque_no" class="form-label">Cheque No:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                                            <input type="text" class="form-control" name="cheque_no"  value="{{ old('cheque_no', $receipt->cheque_no) }}"
                                                placeholder="Enter Cheque No" id="cheque_no">
                                        </div>
                                    </div>

                                    <!-- Cheque Date (hidden initially) -->
                                    <div class="col-md-6 mb-3" id="cheque_date_div" style="display:none;">
                                        <label for="cheque_date" class="form-label">Cheque Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="cheque_date"  value="{{ old('cheque_date', $receipt->cheque_date) }}"
                                                id="to_date">
                                        </div>
                                    </div>

                                    <!-- Bkash Number -->
                                    <div class="col-md-6 mb-3" id="bkash_number_div" style="display:none;">
                                        <label for="bkash_number" class="form-label">Bkash Number:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                            <input type="text" class="form-control" name="bkash_number" value="{{ old('bkash_number', $receipt->bkash_number) }}"
                                                placeholder="Enter Bkash Number" id="bkash_number">
                                        </div>
                                    </div>

                                    <!-- Reference Number -->
                                    <div class="col-md-6 mb-3" id="reference_no_div" style="display:none;">
                                        <label for="reference_no" class="form-label">Reference Number:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                            <input type="text" class="form-control" name="reference_no" value="{{ old('reference_no', $receipt->reference_no) }}"
                                                placeholder="Enter Reference Number" id="reference_no">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3" id="bkash_date_div" style="display:none;">
                                        <label for="bkash_date_div" class="form-label">Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" name="bkash_date" value="{{ old('bkash_date', $receipt->bkash_date) }}" id="from_date">
                                        </div>
                                    </div>

                                    <!-- Receive Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_date" class="form-label">Receive Date:</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="date" id="payment_date" class="form-control"
                                                name="payment_date"
                                                value="{{ old('payment_date', \Carbon\Carbon::parse($receipt->payment_date)->format('Y-m-d')) }}"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Note -->
                                    <div class="col-lg-12 col-md-12 mb-3">
                                        <label for="note">Note</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                            <textarea id="description" name="note" class="form-control" placeholder="Enter note" rows="3">{{ old('note', $receipt->note) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Payment
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
        $(document).ready(function() {
            $('.select2').select2();
        });

        // init select2
        $('#project_id').select2({
            width: '100%'
        });

        // set reference on load
        function setRef() {
            var ref = $('#project_id').find(':selected').data('reference') || '';
            $('#reference_no').val(ref);
        }

        setRef(); // load time

        // bind change event using select2 event
        $('#project_id').on('change', function() {
            setRef();
        });
    </script>

    <!-- JS to toggle visibility -->
   <script>
document.addEventListener("DOMContentLoaded", function() {
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

    // Function to hide all optional fields
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

    // Function to show fields based on mood
    function showFieldsByMood(mood) {
        hideAllPaymentMoodFields();
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
    }

    // Handle Payment Method Change
    paymentMethodSelect.addEventListener('change', function() {
        const paymentType = this.options[this.selectedIndex]?.getAttribute('data-type');

        if (paymentType === 'Bank') {
            paymentMoodSelect.closest('.mb-3').style.display = 'block';
        } else {
            paymentMoodSelect.closest('.mb-3').style.display = 'none';
            hideAllPaymentMoodFields();
        }
    });

    // Handle Payment Mood Change
    paymentMoodSelect.addEventListener('change', function() {
        showFieldsByMood(this.value);
    });

    // -----------------------------
    // ✅ INIT on page load
    // -----------------------------
    const initialPaymentType = paymentMethodSelect.options[paymentMethodSelect.selectedIndex]?.getAttribute('data-type');
    const initialMood = paymentMoodSelect.value;

    if (initialPaymentType === 'Bank') {
        paymentMoodSelect.closest('.mb-3').style.display = 'block';
    } else {
        paymentMoodSelect.closest('.mb-3').style.display = 'none';
    }

    if (initialMood) {
        showFieldsByMood(initialMood);
    }
});
</script>

    <script>
        let activeProductSelect = null;

        function generateProductCode() {
            const randomStr = Math.random().toString(36).substring(2, 7).toUpperCase();
            return 'PRD' + randomStr;
        }

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const projectSelect = document.getElementById("project_id");
            const refInput = document.getElementById("reference_no");

            projectSelect.addEventListener("change", function() {
                let selectedOption = this.options[this.selectedIndex];
                let refNo = selectedOption.getAttribute("data-reference") || "";

                // Auto set ref no
                refInput.value = refNo;
            });
        });
    </script>
@endpush
