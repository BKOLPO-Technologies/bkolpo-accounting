<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    protected $guarded = [];
    //protected $fillable = ['group_id', 'ledger_id'];
    
    // Relationship: A ledger group belongs to a ledger
    // public function ledgers()
    // {
    //     return $this->belongsToMany(Ledger::class, 'ledger_group_details', 'group_id', 'ledger_id');
    // }

    public function ledgerGroupDetails()
    {
        //return $this->hasMany(LedgerGroupDetail::class);
        return $this->hasMany(LedgerGroupDetail::class, 'group_id');
    }

    // Define the relationship to LedgerSubGroup
    public function subGroups()
    {
        return $this->hasMany(LedgerSubGroup::class, 'ledger_group_id');
    }

    public function ledgers()
    {
        return $this->belongsToMany(Ledger::class, 'ledger_group_subgroup_ledgers', 'group_id', 'ledger_id');
    }

    
}
