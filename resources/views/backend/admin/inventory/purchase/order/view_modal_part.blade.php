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
           <h4 style="text-align: right;">
                <img 
                    src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}" 
                    alt="Company Logo" 
                    style="height: 50px; vertical-align: middle; margin-right: 10px;"
                >
                {{-- {{ get_company()->name ?? '' }} --}}
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
    <div class="table-responsive">
    <table class="table table-sm table-striped" style="font-size: 17px; border-collapse: collapse; width:100%;">
        <thead>
            <tr style="border:0.5px solid #dee2e6; background:#f8f9fa;">
                <th class="p-1">Product</th>
                <th class="p-1" style="border:0.5px solid #dee2e6;">Product Code</th>
                <th class="p-1" style="border:0.5px solid #dee2e6;">Specifications</th>
                <th class="p-1" style="border:0.5px solid #dee2e6;">Unit Price</th>
                <th class="p-1" style="border:0.5px solid #dee2e6;">Quantity</th>
                <th class="p-1" style="border:0.5px solid #dee2e6;">Subtotal</th>
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
                    <td class="p-1" style="border:0.5px solid #dee2e6; border-left:none;">{{ $product->name }}</td>
                    <td class="p-1" style="border:0.5px solid #dee2e6;">{{ $product->product_code ?? '' }}</td>
                    <td class="p-1" style="border:0.5px solid #dee2e6;">{{ $product->description }}</td>
                    <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">{{ number_format($product->pivot->price, 2) }}</td>
                    <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">{{ $product->pivot->quantity }} ({{ $product->unit->name }})</td>
                    <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">{{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        @php
            $transportCost = $purchase->transport_cost ?? 0;
            $carryingCharge = $purchase->carrying_charge ?? 0;
            $vat = $purchase->vat_amount ?? 0;
            $tax = $purchase->tax_amount ?? 0;
            $totalDiscount = $purchase->discount ?? 0;
            $totalVatTax = ($transportCost + $carryingCharge + $vat + $tax) - $totalDiscount;
            $totalTotal = $total + $totalVatTax;
        @endphp
        <tfoot>
            <tr>
                <td colspan="4" rowspan="5" style="border:0.5px solid #dee2e6; vertical-align: top; border-left:none;">
                    <p style="margin: 0px;"><b>Project Code:</b> {{ $purchase->invoice_no ?? '' }}</p>
                    <p style="margin: 0px;"><b>Quotation Number:</b> {{ $purchase->quotation_number ?? '' }}</p>
                    <p style="margin: 0px;"><b>Requester Name:</b> {{ $purchase->requester_name ?? '' }}</p>
                    <p style="margin: 0px;"><b>Cost Center:</b> {{ $purchase->cost_center ?? '' }}</p>
                </td>
                <th style="border:0.5px solid #dee2e6;" class="text-right">Subtotal</th>
                <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($total, 2) }}</th>
            </tr>
            <tr>
                <th style="border:0.5px solid #dee2e6;" class="text-right">VAT</th>
                <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($vat ?? 0, 2) }}</th>
            </tr>
            <tr>
                <th style="border:0.5px solid #dee2e6;" class="text-right">Tax</th>
                <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($tax ?? 0, 2) }}</th>
            </tr>
            <tr>
                <th style="border:0.5px solid #dee2e6;" class="text-right">Discount</th>
                <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($totalDiscount ?? 0, 2) }}</th>
            </tr>
            <tr>
                <th style="border:0.5px solid #dee2e6;" class="text-right">Total Purchase Amount</th>
                <th style="border:0.5px solid #dee2e6;" class="text-left">
                    {{ number_format($totalTotal ?? 0, 2) }}
                </th>
            </tr>
        </tfoot>
    </table>
    <div>
        <strong style="margin: 0px;">Amount in Words:</strong>
        <strong>{{ convertNumberToWords($totalTotal) }}</strong>
    </div>
</div>
<div>
    <strong>Terms & Conditions:</strong>
    <p style="margin: 0px;">{!! $purchase->project->terms_conditions ?? '' !!}</p>
</div>

<!-- Signatures -->
<table class="table table-sm" style="margin-top: 20px; text-align: center; font-size: 13px; border:0.5px solid #dee2e6; border-collapse: collapse; width:100%;">
    <tr>
        <td style="border:0.5px solid #dee2e6;">Prepared By</td>
        <td style="border:0.5px solid #dee2e6;">Checked By</td>
        <td style="border:0.5px solid #dee2e6;">Approved By</td>
        <td style="border:0.5px solid #dee2e6;">Managing Director</td>
    </tr>
   <tr style="height: 80px;">
        <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left;"> 
           <div style="border-top:0.5px solid #dee2e6; padding-top:10px;">
                Date: <span style="display:inline-block; width:85%; border-bottom:1px dotted #dee2e6;"></span>
            </div>
        </td>
        <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left;"> 
           <div style="border-top:0.5px solid #dee2e6; padding-top:10px;">
                Date: <span style="display:inline-block; width:85%; border-bottom:1px dotted #dee2e6;"></span>
            </div>
        </td>
        <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left;"> 
           <div style="border-top:0.5px solid #dee2e6; padding-top:10px;">
                Date: <span style="display:inline-block; width:85%; border-bottom:1px dotted #dee2e6;"></span>
            </div>
        </td>
        <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left;"> 
           <div style="border-top:0.5px solid #dee2e6; padding-top:10px;">
                Date: <span style="display:inline-block; width:89%; border-bottom:1px dotted #dee2e6;"></span>
            </div>
        </td>
    </tr>
</table>

</div>

<!-- Print Script -->
<script>
   function printInvoice() {
    const printContents = document.getElementById('printableArea').outerHTML;
    
    const html = `
        <html>
        <head>
            <title>Print Invoice</title>
            <style>
                @media print {
                    body {
                        margin: 15mm 0mm 15mm 0mm; /* top, right, bottom, left */
                    }
                }
            </style>
        </head>
        <body>
            ${printContents}
        </body>
        </html>
    `;

    const originalContents = document.body.innerHTML;
    document.body.innerHTML = html;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

</script>
