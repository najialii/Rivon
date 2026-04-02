<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    //
    protected $fillable = ['supplier_name_en','supplier_name_ar','country', 'phone_num', 'email',
        'address_en', 'address_ar'
    ];

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }
}
