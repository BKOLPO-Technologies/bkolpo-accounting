<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'status', 
        'priority', 
        'customer_id', 
        'customerview', 
        'customercomment', 
        'budget', 
        'phase', 
        'start_date', 
        'end_date', 
        'link_to_calendar', 
        'color', 
        'note', 
        'tags', 
        'task_communication'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_employee', 'project_id', 'employee_id');
    }

    // public function employees()
    // {
    //     return $this->belongsToMany(Employee::class);
    // }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
