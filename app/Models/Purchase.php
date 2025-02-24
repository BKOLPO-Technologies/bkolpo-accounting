<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];
    
    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product') // Pivot table name
                    ->withPivot('quantity', 'price') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
