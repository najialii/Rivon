<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory ,  LogsActivity;

    protected $fillable = ['sku', 'name_ar', 'brand_id', 'name_en',
     'description_ar', 'description_en', 'img_path', 'category_id', 'munit', "status"];

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function brand()
{
    return $this->belongsTo(Brand::class);
}

public function price()
{
    return $this->hasOne(Price::class);


}

public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_en', 'name_ar', 'sku', 'status']) // Fields to watch
            ->logOnlyDirty() // Only log if something actually changed
            ->dontSubmitEmptyLogs();
    }


}