<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Order_item extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'original_price',
        'wholesale_price',
        'retail_price',
        'wholesale_min_price',
        'price',
        'currency',
        'cost_breakdown',
    ];

    protected $casts = [
    'cost_breakdown' => 'array',
];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class, 'order_item_id');
    }


public function getTotalCostAttribute()
{
    return $this->costs()->sum('cost_price');
}



protected static function booted()
{
    static::created(function ($orderItem) {
        $templates = \App\Models\Cost::query()
            ->where('product_id', $orderItem->product_id)
            ->whereNull('order_item_id')
            ->get();

        foreach ($templates as $template) {
            $orderItem->costs()->create([
                'name_ar' => $template->name_ar,
                'name_en' => $template->name_en,
                'description_ar' => $template->description_ar,
                'description_en' => $template->description_en,
                'cost_price' => $template->cost_price,
                'currency' => $template->currency,
                'cost_type' => $template->cost_type,
            ]);
        }
    });
}



  
}
