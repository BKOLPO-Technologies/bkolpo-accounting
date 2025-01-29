<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroupDetail extends Model
{
    // Define the fields that are mass-assignable
    protected $fillable = ['group_id', 'ledger_id'];
  
    /**
     * Get the ledger associated with the ledger group detail.
     */

    public function ledger() {
        return $this->belongsTo(Ledger::class);
    }

    public function group() {
        return $this->belongsTo(LedgerGroup::class, 'group_id');
    }
}
