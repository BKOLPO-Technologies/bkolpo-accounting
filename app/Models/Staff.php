<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'department',
        'designation',
        'join_date',
        'profile_image',
        'cv',
        'address',
        'status'
    ];

    protected $casts = [
        'join_date' => 'date',
        'salary' => 'decimal:2'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function salaries()
    {
        return $this->hasMany(StaffSalary::class, 'staff_id');
    }

    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class, 'staff_id', 'id');
    }
    

}
