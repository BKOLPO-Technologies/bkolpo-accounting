<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // Define the relationship with the Purchase model
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
                    ->withPivot('quantity', 'price') // Access pivot data (quantity and price)
                    ->withTimestamps(); // Keep track of created_at and updated_at
    }
    
    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_product') // Define pivot table name
                    ->withPivot('quantity', 'price') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically manage created_at and updated_at timestamps
    }

    // Relationship with SaleProduct
    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'product_id');
    }

    // Relationship with StockIn
    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'product_id');
    }

    // Relationship with StockOut
    public function stockOuts()
    {
        return $this->hasMany(StockOut::class, 'product_id');
    }
}
