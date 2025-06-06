<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $guarded = [];

    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'quotation_product') // Pivot table name
                    ->withPivot('quantity', 'price', 'discount') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
     }
 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
