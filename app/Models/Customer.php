<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = ['name_ar', 'name_en', 'phone', 'email', 'c_type', 'address_ar', 'address_en'];

    public function loyaltyTransactions()
    {
        return $this->hasMany(Loyalitypt::class);
    }

    public function getLoyaltyBalanceAttribute()
    {
        // Sum all earned minus all spent
        return $this->loyaltyTransactions()->sum('points_earned') -
               $this->loyaltyTransactions()->sum('points_spent');
    }
}
