<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> Bkolpo Constructions Ltd.
                {{-- <small class="float-right">{{ now()->format('d M Y') }}</small> --}}
            </h4>
        </div>
    </div>
    <hr>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <strong>Supplier:</strong><br>
            {{ $purchase->supplier->name }}<br>
            {{ $purchase->supplier->address }}, {{ $purchase->supplier->city }}<br>
            Phone: {{ $purchase->supplier->phone }}<br>
            Email: {{ $purchase->supplier->email }}
        </div>
        <div class="col-sm-4 invoice-col">
            <b>PO No: {{ $purchase->invoice_no }}</b><br>
            Date: {{ \Carbon\Carbon::parse($purchase->invoice_date)->format('d F Y') }}
        </div>
    </div>

    <br>

    <!-- Purchase Details -->
    <div style="border: 1px solid #dbdbdb;">
        <h4 class="text-center mt-2 mb-3" style="
            text-decoration: underline; 
            text-decoration-color: #3498db; /* Change underline color */
            text-decoration-thickness: 3px; /* Adjust underline thickness */
            text-decoration-skip-ink: auto; /* Ensures the underline doesn't go through descenders like 'g', 'j' */
        ">
            <strong>Purchase Details</strong>
        </h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Product Code</th>
                        <th>Speciphication</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $total = 0; 
                    @endphp

                    @foreach ($purchase->products as $product)
                        @php
                            $subtotal = (($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount);
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->product_code ?? '' }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ number_format($product->pivot->price, 2) }}</td>
                            <td>{{ $product->pivot->quantity }} ({{ $product->unit->name }})</td>
                            <td>{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach

                    @php
                        // Fetch additional costs from the purchase table
                        $transportCost = $purchase->transport_cost ?? 0;
                        $carryingCharge = $purchase->carrying_charge ?? 0;
                        $vat = $purchase->vat_amount ?? 0;
                        $tax = $purchase->tax_amount ?? 0;
                        $totalDiscount = $purchase->discount ?? 0;

                        $totalVatTax = ($transportCost + $carryingCharge + $vat + $tax) - $totalDiscount;
                        $totalTotal = $total + $totalVatTax;
                    @endphp
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Subtotal:</th>
                        <th>{{ number_format($total, 2) }}</th>
                    </tr>
                    {{-- <tr>
                        <th colspan="3" class="text-right">Transport Cost:</th>
                        <th>{{ number_format($transportCost, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-right">Carrying Charge:</th>
                        <th>{{ number_format($carryingCharge, 2) }}</th>
                    </tr> --}}
                    <tr>
                        <th colspan="5" class="text-right">VAT:</th>
                        <th>{{ number_format($vat, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">TAX:</th>
                        <th>{{ number_format($tax, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Discount:</th>
                        <th>-{{ number_format($totalDiscount, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Total Purchase Amount:</th>
                        <th>{{ number_format($totalTotal, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <br>

    <!-- Payment Details -->
    <div style="border: 1px solid #dbdbdb;">
        <h4 class="text-center mt-2 mb-3" style="
            text-decoration: underline; 
            text-decoration-color: #3498db; /* Change underline color */
            text-decoration-thickness: 3px; /* Adjust underline thickness */
            text-decoration-skip-ink: auto; /* Ensures the underline doesn't go through descenders like 'g', 'j' */
        ">
            <strong>Payment Details</strong>
        </h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Bank Account No</th>
                        <th>Cheque No</th>
                        <th>Pay Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPayment = 0; @endphp
                    @foreach ($payments as $payment)
                        @php
                            $subtotalPayment = $payment->pay_amount;
                            $totalPayment += $subtotalPayment;
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->bank_account_no ?? '-' }}</td>
                            <td>{{ $payment->cheque_no ?? '-' }}</td>
                            <td>{{ number_format($payment->pay_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total Paid Amount:</th>
                        <th>{{ number_format($totalPayment, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <br>

    <!-- Summary Calculation -->
    <div style="border: 1px solid #dbdbdb;">
        <h4 class="text-center mt-2 mb-3" style="
            text-decoration: underline; 
            text-decoration-color: #3498db; /* Change underline color */
            text-decoration-thickness: 3px; /* Adjust underline thickness */
            text-decoration-skip-ink: auto; /* Ensures the underline doesn't go through descenders like 'g', 'j' */
        ">
            <strong>Summary</strong>
        </h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Total Purchase Amount:</th>
                        <td>{{ number_format($totalTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Paid Amount:</th>
                        <td>{{ number_format($totalPayment, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Payable Amount:</th>
                        <td><strong>{{ number_format($totalTotal - $totalPayment, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
