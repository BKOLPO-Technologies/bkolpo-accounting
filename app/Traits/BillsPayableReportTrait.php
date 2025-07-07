<?php

namespace App\Traits;

use App\Models\PurchaseInvoice;
use Carbon\Carbon;

trait BillsPayableReportTrait
{
    public function getBillsPayableReport($fromDate, $toDate)
    {
        return PurchaseInvoice::with(['supplier'])
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($purchase) {
                return [
                    'invoice_no'    => $purchase->invoice_no,
                    'supplier_name' => optional($purchase->supplier)->name,
                    'invoice_date'  => Carbon::parse($purchase->invoice_date)->format('j F Y'),
                    'grand_total'   => (float) $purchase->grand_total,
                    'paid_amount'   => (float) $purchase->payments->sum('pay_amount'),
                    'due_amount'    => (float) ($purchase->grand_total - $purchase->payments->sum('pay_amount')),
                ];
            })
            ->filter(function ($item) {
                return $item['due_amount'] > 0; // Only show unpaid or partially paid
            });
    }
}
