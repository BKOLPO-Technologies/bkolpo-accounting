<?php

namespace App\Traits;

use App\Models\PurchaseInvoice;
use App\Models\Sale;
use Carbon\Carbon;

trait PurchaseSalesReportTrait
{
    public function getPurchasesReport2($fromDate, $toDate)
    {
        return PurchaseInvoice::with('supplier')
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($purchase) {
                return [
                    'type' => 'Purchase',
                    'invoice_no' => $purchase->invoice_no,
                    'supplier_name' => optional($purchase->supplier)->name,
                    'invoice_date' => Carbon::parse($purchase->invoice_date)->format('j F Y'), 
                    'debit' => (float) $purchase->grand_total,
                    'credit' => 0,
                ];
            });
    }

    public function getSalesReport2($fromDate, $toDate)
    {
        return Sale::with('client')
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get()
            ->map(function ($sale) {
                return [
                    'type' => 'Sale',
                    'invoice_no' => $sale->invoice_no,
                    'supplier_name' => optional($sale->client)->name,
                    'invoice_date' => Carbon::parse($sale->invoice_date)->format('j F Y'), 
                    'debit' => 0,
                    'credit' => (float) $sale->grand_total,
                ];
            });
    }
}
