<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $guarded = [];

    /**
     * Get the product that owns the inventory record.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the supplier that owns the inventory record.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the sale that owns the inventory record.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
