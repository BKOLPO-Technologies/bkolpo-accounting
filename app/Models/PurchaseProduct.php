<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $table = 'purchase_product'; // Define the table name if it's different from plural of model

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'discount',
    ];
}
