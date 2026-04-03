<?php

namespace App\Models;

use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'currency',
        'code'
    ];


    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

  
    protected static function booted()
{
    static::created(function ($salary) {
        $salaryExpenseAccount = \App\Models\Account::where('code', 'salary_expense')->first();
        $cashAccount = \App\Models\Account::where('code', 'cash_on_hand')->first();

        if ($salaryExpenseAccount && $cashAccount) {
            FinanceService::processTransaction(
                reference: $salary,
                debitAccountId: $salaryExpenseAccount->id,  
                creditAccountId: $cashAccount->id,
                amount: $salary->amount,
                descEn: "Salary payment for Employee #" . $salary->employee_id,
                descAr: "صرف راتب للموظف رقم: " . $salary->employee_id,
                currency: $salary->currency
            );
        } else {
            \Log::error("Finance Error: Required accounts (salary_expense/cash_on_hand) missing from COA.");
        }
    });
}

}