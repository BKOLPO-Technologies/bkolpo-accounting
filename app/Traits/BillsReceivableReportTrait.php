<?php

namespace App\Traits;

use App\Models\Sale;
use Carbon\Carbon;

trait BillsReceivableReportTrait
{
    public function getBillsReceivableReport($fromDate, $toDate)
    {
        return Sale::with(['client', 'receipts']) 
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($sale) {
                $paidAmount = (float) $sale->sum('paid_amount');
                $grandTotal = (float) $sale->grand_total;
                $dueAmount = $grandTotal - $paidAmount;

                return [
                    'invoice_no'    => $sale->invoice_no,
                    'client_name'   => optional($sale->client)->name,
                    'invoice_date'  => Carbon::parse($sale->invoice_date)->format('j F Y'),
                    'grand_total'   => $grandTotal,
                    'paid_amount'   => $paidAmount,
                    'due_amount'    => $dueAmount,
                ];
            })
            ->filter(function ($item) {
                return $item['due_amount'] > 0;
            });
    }
}
