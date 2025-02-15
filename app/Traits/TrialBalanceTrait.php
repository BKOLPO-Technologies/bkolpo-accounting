<?php
namespace App\Traits;

use App\Models\JournalVoucherDetail;

trait TrialBalanceTrait
{
    public function getTrialBalance($fromDate, $toDate)
    {
        return JournalVoucherDetail::with('ledger')
        ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
            // If both fromDate and toDate are provided, use whereBetween
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        })
        ->when(!$fromDate || !$toDate || $toDate == now()->format('Y-m-d'), function ($query) use ($toDate) {
            // If fromDate or toDate is not provided or toDate is today, apply orWhere with greater than condition
            $query->orWhere('created_at', '>', $toDate);
        })
        ->selectRaw('ledger_id, SUM(debit) as total_debit, SUM(credit) as total_credit')
        ->groupBy('ledger_id')
        ->get()
        ->map(function ($detail) {
            return [
                'ledger_name' => $detail->ledger->name ?? 'N/A',
                'debit' => $detail->total_debit,
                'credit' => $detail->total_credit,
                'balance' => $detail->total_debit - $detail->total_credit,
            ];
        });
    }
    
}
