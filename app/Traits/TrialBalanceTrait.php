<?php
namespace App\Traits;

use App\Models\JournalVoucherDetail;

trait TrialBalanceTrait
{
    public function getTrialBalance()
    {
        return JournalVoucherDetail::with('ledger')
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
