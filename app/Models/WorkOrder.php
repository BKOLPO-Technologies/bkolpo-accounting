<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class, 'work_order_product') // Pivot table name
                    ->withPivot('quantity', 'price') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
