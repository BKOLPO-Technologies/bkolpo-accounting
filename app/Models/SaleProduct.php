<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    protected $table = 'sale_product'; // Define the table name if it's different from plural of model

    protected $fillable = [
        'sale_id',
        'product_id',
        'item_id',
        'quantity',
        'price',
        'discount',
    ];

    // Relationship with Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function item()
    {
        return $this->belongsTo(ProjectItem::class, 'item_id');
    }

    
}
