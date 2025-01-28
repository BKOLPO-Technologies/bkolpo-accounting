<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    protected $guarded = [];
    
    // Relationship: A ledger group belongs to a ledger
    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
