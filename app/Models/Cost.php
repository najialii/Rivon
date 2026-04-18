<?php

namespace App\Models;

use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Builder;
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
        // --- New expense fields ---
        'expense_date',
        'paid_by_user_id',
        'status',
        'is_standalone',
        'receipt_path',
    ];

    protected $casts = [
        'cost_price'    => 'decimal:2',
        'expense_date'  => 'date',
        'is_standalone' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(Order_item::class, 'order_item_id');
    }

    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'expense_account_id');
    }

    /** The staff member who paid / recorded this expense. */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /** Only standalone expense records (not product/order costs). */
    public function scopeExpenses(Builder $query): Builder
    {
        return $query->where('is_standalone', true);
    }

    /** Only product/order-linked cost records. */
    public function scopeLinkedCosts(Builder $query): Builder
    {
        return $query->where('is_standalone', false);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /** Outstanding amount (alias for cost_price — useful for display). */
    public function getAmountAttribute(): float
    {
        return (float) $this->cost_price;
    }

    // ─── Lifecycle hooks ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::created(function (self $cost) {
            // Only post a journal entry for standalone, approved expenses
            // that have an expense account configured.
            if (! $cost->is_standalone || $cost->status !== 'approved') {
                return;
            }

            if (! $cost->expense_account_id) {
                return;
            }

            FinanceService::postTransaction(
                reference: $cost,
                event: 'expense_recorded',
                entryDate: $cost->expense_date?->copy() ?? now(),
                currency: $cost->currency,
                memoEn: "Expense: {$cost->name_en}",
                memoAr: "مصروف: {$cost->name_ar}",
                lines: [
                    [
                        // Debit the expense GL account
                        'account_id'     => $cost->expense_account_id,
                        'debit'          => (float) $cost->cost_price,
                        'credit'         => 0,
                        'description_en' => "Expense recorded: {$cost->name_en}",
                        'description_ar' => "تسجيل مصروف: {$cost->name_ar}",
                    ],
                    [
                        // Credit Cash / Bank (account code 1010)
                        'account_code'   => '1010',
                        'debit'          => 0,
                        'credit'         => (float) $cost->cost_price,
                        'description_en' => "Cash paid for expense: {$cost->name_en}",
                        'description_ar' => "نقدية مدفوعة للمصروف: {$cost->name_ar}",
                    ],
                ],
            );
        });
    }
}
