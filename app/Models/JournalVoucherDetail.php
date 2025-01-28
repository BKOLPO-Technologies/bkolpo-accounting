<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalVoucherDetail extends Model
{
    protected $fillable = [
        'journal_voucher_id',
        'ledger_id',
        'reference_no',
        'description',
        'debit',
        'credit',
    ];

    public function journalVoucher()
    {
        return $this->belongsTo(JournalVoucher::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
