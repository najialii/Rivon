<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'account_type',
        'description_ar',
        'code',
        'description_en',
        'currency'];

//     public function account(): BelongsTo {
//     return $this->belongsTo(Account::class);
// }

    public function parent(): BelongsTo {
    return $this->belongsTo(Account::class, 'parent_id');
}

    public function entries(): HasMany
    {
        return $this->hasMany(Jentry::class, 'account_id');
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
