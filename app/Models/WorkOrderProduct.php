<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderProduct extends Model
{
    protected $table = 'work_order_product'; // Define the table name if it's different from plural of model

    protected $fillable = [
        'work_order_id ',
        'product_id',
        'quantity',
        'price',
    ];
}
