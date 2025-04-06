<?php

namespace App\Traits;

use App\Models\Sales;
use App\Models\Payment;

trait CalculatesClientPurchases
{
    /**
     * Calculate the total purchase amount for the supplier.
     */
    public function totalPurchaseAmount()
    {
        return $this->sales()->sum('total');
    }

    /**
     * Calculate the total paid amount for the supplier.
     */
    public function totalPaidAmount()
    {
        return $this->payments()->sum('pay_amount');
    }

    /**
     * Calculate the total due amount for the supplier.
     */
    public function totalDueAmount()
    {
        return $this->totalPurchaseAmount() - $this->totalPaidAmount();
    }

    public function totalDiscount()
    {
        return $this->sales()->sum('discount');
    }
}
