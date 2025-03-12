<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerSubGroup extends Model
{
    protected $guarded = [];

    // Define the relationship to LedgerGroup
    public function ledgerGroup()
    {
        return $this->belongsTo(LedgerGroup::class);
    }
}
