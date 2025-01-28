<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroupDetail extends Model
{
    // Define the fields that are mass-assignable
    protected $fillable = ['ledger_group_id', 'ledger_id'];

    /**
     * Get the ledger group that owns the ledger group detail.
     */
    public function ledgerGroup()
    {
        return $this->belongsTo(LedgerGroup::class);
    }

    /**
     * Get the ledger associated with the ledger group detail.
     */
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
