<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntrie extends Model
{
    protected $guarded = [];

    // Define the inverse relationship for the credit branch
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
