<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company', 'phone', 'email', 'address', 'city', 'region', 
        'country', 'postbox', 'taxid', 'customergroup', 'name_s', 'phone_s', 
        'email_s', 'address_s', 'city_s', 'region_s', 'country_s', 'postbox_s',
    ];
}
