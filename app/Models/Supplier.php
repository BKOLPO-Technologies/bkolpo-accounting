<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Supplier extends Model
{
    protected $guarded = [];

    // Define relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class); // Each supplier has many purchases
    }

    // Get Total Purchases for a supplier
    public function totalPurchases()
    {
        return $this->purchases()
                    ->join('purchase_product', 'purchases.id', '=', 'purchase_product.purchase_id')
                    ->sum(DB::raw('(purchase_product.quantity * purchase_product.price) - purchase_product.discount'));
    }


    // Get Total Due for a supplier (Amount remaining)
    public function totalDue()
    {
        return $this->purchases()
                    ->leftJoin('payments', 'purchases.invoice_no', '=', 'payments.invoice_no')
                    ->selectRaw('SUM(purchases.total) - SUM(payments.pay_amount) as due_amount')
                    ->groupBy('purchases.invoice_no')
                    ->sum('due_amount');
    }
}

