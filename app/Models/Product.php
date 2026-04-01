<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'img_path', 'category_id', 'munit'];

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    function category()
    {
        return $this->belongsTo(Category::class);
    }
}
