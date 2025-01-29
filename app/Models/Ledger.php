<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $guarded = [];

    // Relationship: A ledger can have many ledger groups
    public function ledgerGroups()
    {
        return $this->hasMany(LedgerGroup::class);
    }

    public function groups() {
        return $this->belongsToMany(LedgerGroup::class, 'ledger_group_details', 'ledger_id', 'group_id');
    }

    public function journalVoucherDetails()
    {
        return $this->hasMany(JournalVoucherDetail::class, 'ledger_id', 'id');
    }
}
