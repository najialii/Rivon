<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Supply extends Model
{
    protected $fillable = [ 'product_id', 'origin_type', 'cost_id', 'recived_qty', 'expiry_date', 'recived_date'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function cost(): HasMany
    {
        return $this->hasMany(Cost::class);
    }
}