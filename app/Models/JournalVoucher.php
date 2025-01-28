<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
{
    protected $fillable = [
        'transaction_code',
        'company_id',
        'branch_id',
        'transaction_date',
    ];

    public function details()
    {
        return $this->hasMany(JournalVoucherDetail::class);
    }
}
