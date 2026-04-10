<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'cost_price','currency','supply_id', 'cost_type'];

public function orderItem(): BelongsTo
{
    return $this->belongsTo(Order_item::class, 'order_item_id');
}

protected static function booted()
{
    static::created(function ($Cost) {
        $salaryExpenseAccount = \App\Models\Account::where('code', 'salary_expense')->first();
        $cashAccount = \App\Models\Account::where('code', 'cash_on_hand')->first();

        if ($salaryExpenseAccount && $cashAccount) {
            FinanceService::processTransaction(
                reference: $Cost,
                debitAccountId: $salaryExpenseAccount->id,  
                creditAccountId: $cashAccount->id,
                amount: $salary->amount,
                descEn: "cost  #" . $salary->employee_id,
                descAr: "صرف راتب للموظف رقم: " . $salary->employee_id,
                currency: $salary->currency
            );
        } else {
            \Log::error("Finance Error: Required accounts (salary_expense/cash_on_hand) missing from COA.");
        }
    });

    }

    }
