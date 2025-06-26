<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_no', 'invoice_no');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_invoice_items') // Pivot table name
                    ->withPivot('quantity', 'price', 'discount') // Access pivot data (quantity, price)
                    ->withTimestamps(); // Automatically handle created_at and updated_at
    }
}
