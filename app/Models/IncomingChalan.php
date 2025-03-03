<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomingChalan extends Model
{
    protected $fillable = [
        'purchase_id',
        'invoice_date',
        'description',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function products()
    {
        return $this->hasMany(IncomingChalanProduct::class);
    }

}
