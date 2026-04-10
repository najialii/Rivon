<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMov extends Model
{
    //
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',      
        'type',          
        'reference_id',  
        'reference_type',
        'notes'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
