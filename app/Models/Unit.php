<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    // Relationship: A unit has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
