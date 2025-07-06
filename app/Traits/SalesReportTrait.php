<?php
namespace App\Traits;

use App\Models\Sale;
use Carbon\Carbon;

trait SalesReportTrait
{
    public function getSalesReport($fromDate, $toDate)
    {
        $sales = Sale::with('client') 
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->select('id', 'invoice_no', 'grand_total', 'paid_amount', 'client_id','invoice_date')
            ->orderBy('invoice_date')
            ->get();

        return $sales->map(function ($sale) {
            return [
                'invoice_no' => $sale->invoice_no,
                'invoice_date' => Carbon::parse($sale->invoice_date)->format('j F Y'), 
                'client_name' => $sale->client->name ?? '',
                'credit' => (float) $sale->grand_total,
            ];
        });
    }
}

