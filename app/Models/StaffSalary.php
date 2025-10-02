<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    protected $fillable = [
        'staff_id',
        'ledger_id',
        'salary_month',
        'basic_salary',
        'hra',
        'medical',
        'conveyance',
        'pf',
        'tax',
        'other_deductions',
        'gross_salary',
        'net_salary',
        'payment_amount',
        'payment_method',
        'payment_date',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }
}
