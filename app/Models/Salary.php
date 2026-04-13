<?php

namespace App\Models;

use App\Services\AccountingService;
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
        AccountingService::postSalaryPayment($salary);
    });
}

}
