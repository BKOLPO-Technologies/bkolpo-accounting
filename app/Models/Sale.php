<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    
    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_product') // Pivot table name
                    ->withPivot('quantity', 'price','discount') // Access pivot data (quantity, price, discount)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // public function saleProducts()
    // {
    //     return $this->belongsToMany(SaleProduct::class);
    // }

    // Relationship with SaleProduct
    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sale_id');
    }

    public function outcomingChalans()
    {
        return $this->hasMany(OutcomingChalan::class, 'sale_id');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'invoice_no', 'invoice_no');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
