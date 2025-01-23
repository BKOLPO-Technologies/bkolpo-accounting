<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $guarded = [];

    // Define an inverse relationship with Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Define an inverse relationship with ExpenseCategory
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
