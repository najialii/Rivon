<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $fillable = ['product_id','warehouse_id',  'total_qty', 'wholesale_recived_qty', 'retail_recived_qty'];

    public function product()
    {
        return $this->belongsTo(Product::class);

// add warehoues id here 


    }
}