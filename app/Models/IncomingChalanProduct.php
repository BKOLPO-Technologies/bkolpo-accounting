<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingChalanProduct extends Model
{
    protected $fillable = [
        'incoming_chalan_id',
        'product_id',
        'quantity',
        'receive_quantity',
    ];

    public function incomingChalan()
    {
        return $this->belongsTo(IncomingChalan::class);
    }
}
