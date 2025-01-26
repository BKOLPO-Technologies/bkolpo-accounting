<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];
    
    // Define a one-to-many relationship with Journal
    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    // Define the inverse relationship for the credit branch
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'credit_branch_id');
    }
}
