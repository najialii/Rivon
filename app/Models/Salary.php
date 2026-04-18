<?php

namespace App\Models;

use App\Services\AccountingService;
use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'amount',
        'currency',
        'code',
        // --- New staff receivable fields ---
        'transaction_type',
        'status',
        'due_date',
        'amount_settled',
        'notes',
        'receivable_account_id',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'amount_settled'  => 'decimal:2',
        'due_date'        => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /** The GL account used for this receivable / advance. */
    public function receivableAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'receivable_account_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /** Only salary payment records (original behaviour). */
    public function scopeSalaries(Builder $query): Builder
    {
        return $query->where('transaction_type', 'salary');
    }

    /** Only staff receivable records (money owed BY staff). */
    public function scopeReceivables(Builder $query): Builder
    {
        return $query->where('transaction_type', 'receivable');
    }

    /** Only salary advance records. */
    public function scopeAdvances(Builder $query): Builder
    {
        return $query->where('transaction_type', 'advance');
    }

    /** Only collection records (money collected BY staff on behalf of company). */
    public function scopeCollections(Builder $query): Builder
    {
        return $query->where('transaction_type', 'collection');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'partial'])
                     ->whereDate('due_date', '<', now());
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** Remaining balance for receivables / advances. */
    public function getOutstandingBalanceAttribute(): float
    {
        return max(0, (float) $this->amount - (float) ($this->amount_settled ?? 0));
    }

    public function isReceivable(): bool
    {
        return in_array($this->transaction_type, ['receivable', 'advance', 'collection']);
    }

    // ─── Lifecycle hooks ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::created(function (self $salary) {

            // ── Original salary logic ────────────────────────────────────────
            if ($salary->transaction_type === 'salary') {
                AccountingService::postSalaryPayment($salary);
                return;
            }

            // ── Staff Receivable / Advance logic ─────────────────────────────
            if (in_array($salary->transaction_type, ['receivable', 'advance']) && $salary->receivable_account_id) {
                $isAdvance = $salary->transaction_type === 'advance';

                FinanceService::postTransaction(
                    reference: $salary,
                    event: $isAdvance ? 'advance_issued' : 'receivable_recorded',
                    entryDate: now(),
                    currency: $salary->currency,
                    memoEn: $isAdvance
                        ? "Salary advance for {$salary->employee->name}"
                        : "Staff receivable for {$salary->employee->name}",
                    memoAr: $isAdvance
                        ? "سلفة راتب للموظف {$salary->employee->name}"
                        : "ذمة مدينة للموظف {$salary->employee->name}",
                    lines: [
                        [
                            // Debit the Staff Receivable / Advances GL account
                            'account_id'     => $salary->receivable_account_id,
                            'debit'          => (float) $salary->amount,
                            'credit'         => 0,
                            'description_en' => $isAdvance ? "Advance issued" : "Receivable recorded",
                            'description_ar' => $isAdvance ? "سلفة صادرة" : "ذمة مسجلة",
                        ],
                        [
                            // Credit Cash / Bank (account code 1010)
                            'account_code'   => '1010',
                            'debit'          => 0,
                            'credit'         => (float) $salary->amount,
                            'description_en' => $isAdvance ? "Cash paid as advance" : "Cash for receivable",
                            'description_ar' => $isAdvance ? "نقدية مدفوعة كسلفة" : "نقدية للذمة",
                        ],
                    ],
                );
            }

            // ── Collection logic (staff collected money on behalf of company) ─
            if ($salary->transaction_type === 'collection' && $salary->receivable_account_id) {
                FinanceService::postTransaction(
                    reference: $salary,
                    event: 'collection_recorded',
                    entryDate: now(),
                    currency: $salary->currency,
                    memoEn: "Collection by staff: {$salary->employee->name}",
                    memoAr: "تحصيل بواسطة الموظف: {$salary->employee->name}",
                    lines: [
                        [
                            // Debit Cash / Bank (money is now in company hands via staff)
                            'account_code'   => '1010',
                            'debit'          => (float) $salary->amount,
                            'credit'         => 0,
                            'description_en' => "Cash collected by staff",
                            'description_ar' => "نقدية محصلة بواسطة الموظف",
                        ],
                        [
                            // Credit the Staff Collection / Receivable account
                            'account_id'     => $salary->receivable_account_id,
                            'debit'          => 0,
                            'credit'         => (float) $salary->amount,
                            'description_en' => "Collection credited",
                            'description_ar' => "قيد التحصيل",
                        ],
                    ],
                );
            }
        });
    }
}
