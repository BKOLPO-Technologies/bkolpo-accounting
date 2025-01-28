<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    protected $guarded = [];
    
    // Relationship: A ledger group belongs to a ledger
    public function ledgers()
    {
        return $this->belongsToMany(Ledger::class, 'ledger_group_details', 'ledger_group_id', 'ledger_id');
    }

    public function ledgerGroupDetails()
    {
        return $this->hasMany(LedgerGroupDetail::class);
    }
    
}
