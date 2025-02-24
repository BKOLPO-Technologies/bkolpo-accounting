<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Define the relationship with the Purchase model
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
                    ->withPivot('quantity', 'price') // Access pivot data (quantity and price)
                    ->withTimestamps(); // Keep track of created_at and updated_at
    }
}
