<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroupSubgroupLedger extends Model
{
    protected $guarded = [];

    // Relationship with Ledger
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    // Relationship with Ledger Group
    public function group()
    {
        return $this->belongsTo(LedgerGroup::class, 'group_id');
    }

    // Relationship with Ledger SubGroup
    public function subGroup()
    {
        return $this->belongsTo(LedgerSubGroup::class, 'sub_group_id');
    }
}
