<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesSupplierPurchases;

class Supplier extends Model
{
    use CalculatesSupplierPurchases;

    protected $guarded = [];

    // Define the relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Define the relationship with payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

   
}

