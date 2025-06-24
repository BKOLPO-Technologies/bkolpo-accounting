<!-- Print Button -->
<div class="text-right mb-3">
    <button onclick="printInvoice()" class="btn btn-primary">
        <i class="fas fa-print"></i> Print
    </button>
</div>

<!-- Printable Invoice -->
<div class="invoice p-3 mb-3" id="printableArea">
    <div class="row">
        <div class="col-12">
            <h4>
                <img 
                    src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                    alt="Company Logo" 
                    style="height: 40px; vertical-align: middle; margin-right: 10px;"
                >
                {{ get_company()->name ?? '' }}
                <small class="float-right" id="current-date"></small>
            </h4>  
        </div>
    </div>
    <hr>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <strong>Vendor:</strong><br>
            {{ $purchase->supplier->name }}<br>
            {{ $purchase->supplier->address }}, {{ $purchase->supplier->city }}<br>
            Phone: {{ $purchase->supplier->phone }}<br>
            Email: {{ $purchase->supplier->email }}
        </div>

        <div class="col-sm-4 invoice-col">
            <b>PO No:</b> {{ $purchase->invoice_no }}<br>
            <b>Date:</b> {{ \Carbon\Carbon::parse($purchase->invoice_date)->format('d F Y') }}
        </div>

        <div class="col-sm-4 invoice-col">
            <strong>Company:</strong><br>
            {{ get_company()->name ?? '' }}<br>
            {{ get_company()->address ?? '' }}, {{ get_company()->city ?? '' }}<br>
            Phone: {{ get_company()->phone ?? '' }}<br>
            Email: {{ get_company()->email ?? '' }}
        </div>
    </div>


    <br>

    <!-- Purchase Details -->
    <div style="border: 1px solid #dbdbdb;">
        <h4 class="text-center mt-2 mb-3" style="text-decoration: underline; text-decoration-color: #3498db; text-decoration-thickness: 3px;">
            <strong>Purchase Order Details</strong>
        </h4>
        <div class="table-responsive" style="overflow-x: hidden;">
            <table class="table table-striped table-sm table-bordered" style="margin: 10px; font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th class="p-1 border">Product</th>
                        <th class="p-1 border">Product Code</th>
                        <th class="p-1 border">Specifications</th>
                        <th class="p-1 border">Unit Price</th>
                        <th class="p-1 border">Quantity</th>
                        <th class="p-1 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($purchase->products as $product)
                        @php
                            $subtotal = (($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount);
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="p-1 border">{{ $product->name }}</td>
                            <td class="p-1 border">{{ $product->product_code ?? '' }}</td>
                            <td class="p-1 border">{{ $product->description }}</td>
                            <td class="p-1 border">{{ number_format($product->pivot->price, 2) }}</td>
                            <td class="p-1 border">{{ $product->pivot->quantity }} ({{ $product->unit->name }})</td>
                            <td class="p-1 border">{{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach

                    @php
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
                        <th colspan="5" class="text-right p-1 border">Subtotal:</th>
                        <th class="p-1 border">{{ number_format($total, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right p-1 border">VAT:</th>
                        <th class="p-1 border">{{ number_format($vat, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right p-1 border">TAX:</th>
                        <th class="p-1 border">{{ number_format($tax, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right p-1 border">Discount:</th>
                        <th class="p-1 border">-{{ number_format($totalDiscount, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right p-1 border">Total Purchase Amount:</th>
                        <th class="p-1 border">{{ number_format($totalTotal, 2) }}</th>
                    </tr>
                </tfoot>
            </table>


            <div class="pl-2 pb-2" style="margin-top: 10px;">
                <strong>Amount in Words:</strong>
                <strong class="text-uppercase">{{ convertNumberToWords($totalTotal) }}</strong>
            </div>
        </div>
        
    </div>

    <div>
        <strong>Terms & Conditions:</strong>
        <p>{!! $purchase->project->terms_conditions ?? '' !!}</p>
    </div>
    <br>
</div>

<!-- Print Script -->
<script>
    function printInvoice() {
        const printContents = document.getElementById('printableArea').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Optional: reload the page to restore state
    }
</script>
