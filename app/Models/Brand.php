<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    //
    protected $fillable = [
    'name_ar', 'name_en', 'slug', 'logo_path', 'is_active'
    ];
}
