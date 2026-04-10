<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Product extends Model
{
    use HasFactory ,  LogsActivity;

    protected $fillable = ['sku', 'name_ar', 'brand_id', 'name_en',
     'description_ar', 'description_en','type', 'orginal_price','currancy', 'wholesale_price', 'retail_price', 'img_path', 'category_id', 'measurement_unit', ' unit_quantity', 'unit_weight', "status"];

    public function supplies()
    {
        return $this->HasMany(Supply::class);
    }

    function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function brand()
{
    return $this->belongsTo(Brand::class);
}



public function stockMovements()
{
    return $this->hasMany(Stockmovement::class);
}




    public function costTemplates(): HasMany
{
    return $this->hasMany(Cost::class)->whereNull('order_item_id');
}


public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name_en', 'name_ar', 'sku', 'status']) // Fields to watch
            ->logOnlyDirty() // Only log if something actually changed
            ->dontSubmitEmptyLogs();
    }


}

