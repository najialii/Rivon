<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [ 'product_id', 'origin_type', 'cost_price', 'recived_qty', 'expiry_date', 'recived_date'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
