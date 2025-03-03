<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutcomingChalan extends Model
{
    protected $fillable = [
        'sale_id',
        'invoice_date',
        'description',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function products()
    {
        return $this->hasMany(OutcomingChalanProduct::class);
    }
}
