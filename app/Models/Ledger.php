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
}
