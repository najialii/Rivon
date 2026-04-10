<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Loyalitypt extends Model
    {
        //
        protected $fillable = 
        [
        'customer_id', 
        'order_id', 
        'points_earned', 
        'points_spent', 
        'reason'
        ];
          public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    } 
    }
