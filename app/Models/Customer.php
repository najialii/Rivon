<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = ['name_ar', 'name_en', 'phone', 'email', 'c_type' , 'address_ar', 'address_en'];
}
