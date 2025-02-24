<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    // Define the relationship with the Sale model
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
