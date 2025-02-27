<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutcomingChalanProduct extends Model
{
    protected $fillable = [
        'outcoming_chalan_id',
        'product_id',
        'quantity',
        'receive_quantity',
    ];

    public function outcomingChalan()
    {
        return $this->belongsTo(OutcomingChalan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
