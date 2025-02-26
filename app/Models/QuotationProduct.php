<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    protected $table = 'quotation_product'; // Define the table name if it's different from plural of model

    protected $fillable = [
        'quotation_id',
        'product_id',
        'quantity',
        'price',
        'discount',
    ];
}
