<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'basic',
        'hra',
        'medical',
        'conveyance',
        'pf',
        'tax',
        'other_deduction',
        'gross',
        'net',
    ];

    /**
     *  salary structure 
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Auto calculate gross & net salary before saving
     */
    protected static function booted()
    {
        static::saving(function ($structure) {
            $structure->gross = $structure->basic + $structure->hra + $structure->medical + $structure->conveyance;
            $structure->net = $structure->gross - ($structure->pf + $structure->tax + $structure->other_deduction);
        });
    }
}
