<?php
namespace App\Traits;

use App\Models\JournalVoucherDetail;

trait SumLedgerAmounts
{
    // single ledger wise sum
    public function getLedgerSums($ledger)
    {
        $ledgerDebit = $ledger->journalVoucherDetails()->sum('debit');
        $ledgerCredit = $ledger->journalVoucherDetails()->sum('credit');

        return [
            'debit' => $ledgerDebit,
            'credit' => $ledgerCredit
        ];
    }

    // total ledger wise sum
    public function getTotalSums($ledgers)
    {
        $totalDebit = $ledgers->sum(function ($ledger) {
            return $ledger->journalVoucherDetails->sum('debit');
        });

        $totalCredit = $ledgers->sum(function ($ledger) {
            return $ledger->journalVoucherDetails->sum('credit');
        });

        return [
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit
        ];
    }
}
