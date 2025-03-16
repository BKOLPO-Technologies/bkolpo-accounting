<!-- Print-specific styles -->
<style>
    @media print {
        /* Hide the Print button */
        button {
            display: none !important;
        }

        /* Remove background color and add rounded corners to the voucher */
        #paySlipContent {
            background-color: transparent !important;
            border-radius: 15px !important;
        }

        /* Reset the table header background color */
        #paySlipContent table thead {
            background-color: transparent !important;
            color: black !important;
        }

        /* Ensure the table is styled properly for print */
        #paySlipContent table {
            border: 1px solid black !important;
        }

        /* Make table borders normal and padding adjusted for print */
        #paySlipContent table th,
        #paySlipContent table td {
            padding: 5px !important;
            border: 1px solid black !important;
        }
        .voucher-header {
            /* text-align: center !important; */
            display: none !important;
        }
    }
</style>
<div id="paySlipContent" style="font-family: Arial, sans-serif; width: 100%; margin: auto; padding: 20px; border: 2px solid #000; background-color: #fef4c5; border-radius: 15px;">
    <h2 style="text-align: center; margin: 0; padding: 10px 0;">
        {{ $company->company_name }} <br>
        <span style="font-size: 14px; display: block; margin-top: 5px;">
            <strong>Head Office:</strong> {{ $company->address }}
        </span>
        <span style="font-size: 14px; display: block; margin-top: 5px;">
            <strong>Hotline:</strong> {{ $company->phone }} | <strong>Email:</strong> {{ $company->email }}
        </span>
        <span style="font-size: 14px; display: block; margin-top: 5px;">
            <strong>Website:</strong> www.bkolpo.com.bd
        </span>
    </h2>

    <div style="text-align: center; margin: 0; padding: 10px 20px; background-color: #28a745; color: white; border-radius: 10px; font-size: 20px; display: inline-block;" class="voucher-header">
        <strong>VOUCHER</strong>
    </div>



    <div style="display: flex; justify-content: space-between; padding: 10px 0;">
        <p><strong>Name:</strong> {{ $ledger->name }}</p>
        <p><strong>Date:</strong> {{ date('d/m/Y') }}</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000;">
        <thead style="background-color: #000; color: white;">
            <tr>
                <th style="border: 1px solid #000; padding: 8px; text-align: center; width: 10%;">SL. No</th>
                <th style="border: 1px solid #000; padding: 8px; text-align: left; width: 60%;">Particulars</th>
                <th style="border: 1px solid #000; padding: 8px; text-align: right; width: 15%;">Debit</th>
                <th style="border: 1px solid #000; padding: 8px; text-align: right; width: 15%;">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ledger->journalVoucherDetails as $key => $detail)
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; text-align: center;">{{ $key + 1 }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: left;">{{ $detail->ledger->name }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: right;"> {{ bdt() }} {{ number_format($detail->debit, 2) }}</td>
                    <td style="border: 1px solid #000; padding: 8px; text-align: right;"> {{ bdt() }} {{ number_format($detail->credit, 2) }}</td>
                </tr>
            @endforeach
            <!-- Sum Row -->
            <tr style="font-weight: bold;">
                <td style="border: 1px solid #000; padding: 8px; text-align: center;" colspan="2">Total</td>
                <td style="border: 1px solid #000; padding: 8px; text-align: right;">
                     {{ bdt() }} {{ number_format($ledger->journalVoucherDetails->sum('debit'), 2) }}
                </td>
                <td style="border: 1px solid #000; padding: 8px; text-align: right;">
                     {{ bdt() }} {{ number_format($ledger->journalVoucherDetails->sum('credit'), 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <p style="padding-top: 10px; text-align: left;">
        <strong>Taka (IN words):</strong> ..........................................................
    </p>

    <div style="margin-top: 20px; display: flex; justify-content: space-between;">
        <p style="border-top: 2px solid #000; padding-top: 10px;"><strong>Prepared By</strong></p>
        <p style="border-top: 2px solid #000; padding-top: 10px;"><strong>Accounts</strong></p>
        <p style="border-top: 2px solid #000; padding-top: 10px;"><strong>Admin</strong></p>
        <p style="border-top: 2px solid #000; padding-top: 10px;"><strong>Director</strong></p>
        <p style="border-top: 2px solid #000; padding-top: 10px;"><strong>Managing Director</strong></p>
    </div>

    <!-- Print Button -->
    <button onclick="printPaySlip()" style="display: block; margin: 20px auto; padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; font-size: 16px;">
        Print Voucher
    </button>
</div>

<!-- Print Function -->
<script>
    function printPaySlip() {
        var printContent = document.getElementById('paySlipContent').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        // window.location.reload();
    }
</script>
