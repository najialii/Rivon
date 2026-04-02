<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'account_type', 'description_ar', 'description_en', 'currancy'];


public function entries(): HasMany
    {
        return $this->hasMany(J_entries::class);
    }

    public function getBalanceAttribute()
    {
        $debits = $this->entries()->sum('debit');
        $credits = $this->entries()->sum('credit');

        if (in_array($this->account_type, ['asset', 'expense'])) {
            return $debits - $credits;
        }

        return $credits - $debits;
    }

    }
