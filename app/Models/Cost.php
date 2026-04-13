<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'product_id',
        'order_item_id',
        'description_ar',
        'description_en',
        'cost_price',
        'currency',
        'cost_type',
        'expense_account_id',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(Order_item::class, 'order_item_id');
    }

    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }
}
