<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

protected $fillable = ['product_id', 'quantity', 'total_price', 'order_date', 'customer_id'];

   function product()
    {
        return $this->belongsTo(Product::class);
    }

    function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
