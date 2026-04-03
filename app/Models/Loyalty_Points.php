<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loyalty_Points extends Model
{
    //
    protected $fillable = ['customer_id', 'points', 'earn_rate', 'redeem_rate', 'expiry_date'];


    function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
