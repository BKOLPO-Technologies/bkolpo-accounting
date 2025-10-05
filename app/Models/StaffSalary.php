<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'salary_month',
        'basic',
        'hra',
        'medical',
        'conveyance',
        'pf',
        'tax',
        'other_deduction',
        'gross',
        'net',
        'payment_amount',
        'status',
    ];

    // Auto calculate gross & net before saving
    protected static function booted()
    {
        static::saving(function ($salary) {
            $salary->gross = $salary->basic + $salary->hra + $salary->medical + $salary->conveyance;
            $salary->net = $salary->gross - ($salary->pf + $salary->tax + $salary->other_deduction);
        });
    }

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    // Optional: Accessor for formatted salary month (for views)
    public function getFormattedMonthAttribute()
    {
        return \Carbon\Carbon::parse($this->salary_month)->format('F Y');
    }
}