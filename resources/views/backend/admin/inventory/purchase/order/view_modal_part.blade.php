<style>
    @media print {
        body {
            margin: 15mm 0mm 15mm 0mm;
            position: relative;
        }

        .printable-content {
            position: relative;
        }

        .terms-and-signature-container {
            position: fixed;
            bottom: 15mm;
            left: 0;
            width: 100%;
            page-break-inside: avoid;
        }

        .terms-conditions {
            margin-bottom: 10px;
        }

        .invoice-signatures {
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .signature-box {
            border-top: 1px solid #999;
            padding-top: 15px;
            height: 100px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            border: 1px solid #dee2e6;
            padding: 5px;
            font-size: 13px;
        }

        /* Avoid breaking the signature across pages */
        .invoice-signatures {
            page-break-inside: avoid !important;
        }

        /* Ensure content doesn't overflow behind the fixed signature section */
        .main-content {
            margin-bottom: 150px;
            /* Adjust based on your signature section height */
        }
    }

    @media screen {
        .terms-and-signature-container {
            margin-top: 20px;
        }
    }
</style>

<!-- Print Button -->
<div class="text-right mb-3">
    <button onclick="printInvoice()" class="btn btn-info">
        <i class="fas fa-print"></i> Print
    </button>
</div>

<!-- Printable Invoice -->
<div class="invoice p-3 mb-3" id="printableArea">
    <div class="row">
        <div class="col-12">
            <h4 style="text-align: right;">
                <img src="{{ !empty(get_company()->logo) ? url('upload/company/' . get_company()->logo) : asset('backend/logo.jpg') }}"
                    alt="Company Logo" style="height: 50px; vertical-align: middle; margin-right: 10px;">
                {{-- {{ get_company()->name ?? '' }} --}}
                <small class="float-right" id="current-date"></small>
            </h4>
        </div>
    </div>
    <hr>
    <div class="row invoice-info">
        <div class="table-responsive p-2">
            <!-- Vendor / Invoice / Company Section -->
            <table style="width:100%; border:0.5px solid #dee2e6; border-collapse: collapse; margin-bottom:0px;">
                <thead>
                    <tr>
                        <!-- Vendor -->
                        <td style="border:0.5px solid #dee2e6; vertical-align:top; width:33%; padding:5px;">
                            <strong>Vendor:</strong><br>
                            {{ $purchase->supplier->name }}<br>
                            {{ $purchase->supplier->address }} {{ $purchase->supplier->city }}<br>
                            Phone: {{ $purchase->supplier->phone }}<br>
                            Email: {{ $purchase->supplier->email }}
                        </td>

                        <!-- Invoice -->
                        <td style="border:0.5px solid #dee2e6; vertical-align:top; width:33%; padding:5px;">
                            <b>PO No:</b> {{ $purchase->invoice_no }}<br>
                            <b>Project Code:</b> {{ $purchase->project->reference_no ?? '' }}<br>
                            <b>Date:</b> {{ \Carbon\Carbon::parse($purchase->invoice_date)->format('d F Y') }}
                        </td>

                        <!-- Company -->
                        <td style="border:0.5px solid #dee2e6; vertical-align:top; width:33%; padding:5px;">
                            <strong>Company:</strong><br>
                            {{ get_company()->name ?? '' }}<br>
                            {{ get_company()->address ?? '' }} {{ get_company()->city ?? '' }}<br>
                            Phone: {{ get_company()->phone ?? '' }}<br>
                            Email: {{ get_company()->email ?? '' }}
                        </td>
                    </tr>
                </thead>
            </table>
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
                            $subtotal = $product->pivot->price * $product->pivot->quantity - $product->pivot->discount;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="p-1" style="border:0.5px solid #dee2e6; border-left:none;">
                                {{ $product->name }}</td>
                            <td class="p-1" style="border:0.5px solid #dee2e6;">{{ $product->product_code ?? '' }}
                            </td>
                            <td class="p-1" style="border:0.5px solid #dee2e6;">{{ $product->description }}</td>
                            <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">
                                {{ number_format($product->pivot->price, 2) }}</td>
                            <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">
                                {{ $product->pivot->quantity }} ({{ $product->unit->name }})</td>
                            <td class="p-1 text-left" style="border:0.5px solid #dee2e6;">
                                {{ number_format($subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @php
                    $transportCost = $purchase->transport_cost ?? 0;
                    $carryingCharge = $purchase->carrying_charge ?? 0;
                    $vat = $purchase->vat_amount ?? 0;
                    $tax = $purchase->tax_amount ?? 0;
                    $include_tax = $purchase->include_tax;
                    $include_vat = $purchase->include_vat;
                    $totalDiscount = $purchase->discount ?? 0;
                    $totalVatTax = $transportCost + $carryingCharge + $vat + $tax - $totalDiscount;
                    $totalTotal = $total + $totalVatTax;
                @endphp
                <tfoot>
                    <tr>
                        <td colspan="4" rowspan="5"
                            style="border:0.5px solid #dee2e6; vertical-align: top; border-left:none;">
                            <b>Contact Person:</b> {{ $purchase->project->project_coordinator ?? '' }} <br>
                            <b>Delivery Location:</b> {{ $purchase->project->project_location ?? '' }}
                        </td>
                        <th style="border:0.5px solid #dee2e6;" class="text-right">Subtotal</th>
                        <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($total, 2) }}</th>
                    </tr>
                    <tr>
                        <th style="border:0.5px solid #dee2e6;" class="text-right">VAT</th>
                        <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($vat ?? 0, 2) }}
                        </th>
                    </tr>
                    <tr>
                        <th style="border:0.5px solid #dee2e6;" class="text-right">TAX</th>
                        <th style="border:0.5px solid #dee2e6;" class="text-left">{{ number_format($tax ?? 0, 2) }}
                        </th>
                    </tr>
                    <tr>
                        <th style="border:0.5px solid #dee2e6;" class="text-right">Discount</th>
                        <th style="border:0.5px solid #dee2e6;" class="text-left">
                            {{ number_format($totalDiscount ?? 0, 2) }}</th>
                    </tr>
                    <tr>
                        <th style="border:0.5px solid #dee2e6;" class="text-right">Total Amount</th>
                        <th style="border:0.5px solid #dee2e6;" class="text-left">
                            {{ number_format($totalTotal ?? 0, 2) }}</th>
                    </tr>
                    <!-- Tax/VAT Condition Left + Remark Right -->
                    <tr>
                        {{-- <td colspan="3" style="border:0.5px solid #dee2e6; text-align:left;">
                            <p style="margin: 0px;">
                                <b>TAX Condition :</b> {{ ($tax ?? 0) > 0 ? 'AIT Inclusive' : 'AIT Exclusive' }}
                            </p>
                            <p style="margin: 0px;">
                                <b>VAT Condition :</b> {{ ($vat ?? 0) > 0 ? 'VAT Inclusive' : 'VAT Exclusive' }}
                            </p>
                        </td> --}}
                        <td colspan="3" style="border:0.5px solid #dee2e6; text-align:left;">
                            <p style="margin: 0px;">
                                <b>TAX Condition :</b>
                                {{ ($include_tax ?? 0) == 1 ? 'AIT Inclusive' : 'AIT Exclusive' }}
                            </p>
                            <p style="margin: 0px;">
                                <b>VAT Condition :</b>
                                {{ ($include_vat ?? 0) == 1 ? 'VAT Inclusive' : 'VAT Exclusive' }}
                            </p>
                        </td>



                        <td colspan="3" style="border:0.5px solid #dee2e6; text-align:left;">
                            <b>Remark :</b> {{ $purchase->description ?? '' }}
                        </td>
                    </tr>

                </tfoot>


            </table>
            <!-- Amount in Words -->
            <table style="width:100%; margin-top:5px; border:0.5px solid #dee2e6; border-collapse: collapse;">
                <tr>
                    <td style="padding:5px;"><strong>Amount in Words:
                            {{ convertNumberToWords($totalTotal) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Terms & Conditions and Signature Section -->
    <div class="terms-and-signature-container">
        <!-- Terms & Conditions Section -->
        <div class="terms-conditions">
            <strong>Terms & Conditions:</strong>
            <p style="margin: 0px;">{!! $purchase->project->terms_conditions ?? '' !!}</p>
        </div>

        <!-- Signature Section -->
        <div class="invoice-signatures">
            <table class="table table-sm"
                style="text-align: center; font-size: 13px; border:0.5px solid #dee2e6; border-collapse: collapse; width:100%; table-layout: fixed;">
                <tr>
                    <td style="border:0.5px solid #dee2e6; width:25%;"><strong>Prepare By</strong></td>
                    <td style="border:0.5px solid #dee2e6; width:25%;"><strong>Checked By</strong></td>
                    <td style="border:0.5px solid #dee2e6; width:25%;"><strong>Approve By</strong></td>
                    <td style="border:0.5px solid #dee2e6; width:25%;"><strong>Received By
                            (Vendor/Subcontractor)</strong>
                    </td>
                </tr>
                <tr style="height: 120px;">
                    <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left; padding:0 5px;">
                        <div style="border-top:0.5px solid #dee2e6; padding-top:15px; padding-bottom:15px;">
                            <span
                                style="display:block; border-bottom:1px dotted #dee2e6; width:100%; height:1.2em;"><strong>Date:</strong></span>
                        </div>
                    </td>
                    <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left; padding:0 5px;">
                        <div style="border-top:0.5px solid #dee2e6; padding-top:15px; padding-bottom:15px;">
                            <span
                                style="display:block; border-bottom:1px dotted #dee2e6; width:100%; height:1.2em;"><strong>Date:</strong></span>
                        </div>
                    </td>
                    <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left; padding:0 5px;">
                        <div style="border-top:0.5px solid #dee2e6; padding-top:15px; padding-bottom:15px;">
                            <span
                                style="display:block; border-bottom:1px dotted #dee2e6; width:100%; height:1.2em;"><strong>Date:</strong></span>
                        </div>
                    </td>
                    <td style="border:0.5px solid #dee2e6; vertical-align: bottom; text-align:left; padding:0 5px;">
                        <div style="border-top:0.5px solid #dee2e6; padding-top:15px; padding-bottom:15px;">
                            <span
                                style="display:block; border-bottom:1px dotted #dee2e6; width:100%; height:1.2em;"><strong>Date:</strong></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
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
                        margin: 15mm 0mm 15mm 0mm;
                        position: relative;
                    }

                    .printable-content {
                        position: relative;
                    }

                    .terms-and-signature-container {
                        position: fixed;
                        bottom: 15mm;
                        left: 4mm; /* adds left margin */
                        right: 4mm; /* adds right margin */
                        width: auto; /* width auto so it respects left/right margins */
                        page-break-inside: avoid;
                    }

                    .terms-conditions {
                        margin-bottom: 10px;
                    }

                    .invoice-signatures {
                        padding-top: 10px;
                    }

                    .signature-box {
                        border-top: 1px solid #999;
                        padding-top: 15px;
                        height: 100px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    table td, table th {
                        border: 1px solid #dee2e6;
                        padding: 5px;
                        font-size: 13px;
                    }

                    .invoice-signatures {
                        page-break-inside: avoid !important;
                    }
                    
                    .main-content {
                        margin-bottom: 150px;
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
