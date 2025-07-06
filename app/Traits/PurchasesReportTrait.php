<?php
namespace App\Traits;

use App\Models\PurchaseInvoice;
use Carbon\Carbon;

trait PurchasesReportTrait
{
    public function getpurchasesReport($fromDate, $toDate)
    {
        $purchases = PurchaseInvoice::with('supplier') 
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->select('id', 'invoice_no', 'grand_total', 'paid_amount', 'supplier_id','invoice_date')
            ->orderBy('invoice_date')
            ->get();

        return $purchases->map(function ($purchase) {
            return [
                'invoice_no' => $purchase->invoice_no,
                'invoice_date' => Carbon::parse($purchase->invoice_date)->format('j F Y'), 
                'supplier_name' => $purchase->supplier->name ?? '',
                'debit' => (float) $purchase->grand_total,
            ];
        });
    }
}

