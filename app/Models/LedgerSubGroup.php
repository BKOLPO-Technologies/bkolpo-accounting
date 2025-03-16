<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerSubGroup extends Model
{
    protected $guarded = [];

    // Define the relationship to LedgerGroup
    public function ledgerGroup()
    {
        return $this->belongsTo(LedgerGroup::class, 'ledger_group_id');
    }

    public function ledgers()
    {
        return $this->belongsToMany(Ledger::class, 'ledger_group_subgroup_ledgers', 'sub_group_id', 'ledger_id');
    }

}
