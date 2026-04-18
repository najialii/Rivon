<?php

namespace App\Models;

use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'amount',
        'currency',
        'payment_date',
        'method',
        'reference',
        'notes',
        // --- New deposit fields ---
        'transaction_type',
        'source_type',
        'deposited_by_user_id',
        'deposit_account_id',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'payment_date' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** The staff user who recorded/made the deposit. */
    public function depositedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deposited_by_user_id');
    }

    /** The GL account the deposit was credited to. */
    public function depositAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'deposit_account_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    /** Only invoice payment records (original behaviour). */
    public function scopePayments(Builder $query): Builder
    {
        return $query->where('transaction_type', 'payment');
    }

    /** Only deposit records. */
    public function scopeDeposits(Builder $query): Builder
    {
        return $query->where('transaction_type', 'deposit');
    }

    /** Deposits made by public (walk-in) users. */
    public function scopePublicDeposits(Builder $query): Builder
    {
        return $query->deposits()->where('source_type', 'public');
    }

    /** Deposits made by internal staff. */
    public function scopeStaffDeposits(Builder $query): Builder
    {
        return $query->deposits()->where('source_type', 'staff');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isDeposit(): bool
    {
        return $this->transaction_type === 'deposit';
    }

    public function isPayment(): bool
    {
        return $this->transaction_type === 'payment';
    }

    // ─── Lifecycle hooks ─────────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::created(function (self $payment) {

            // ── Original payment logic (invoice settlement) ──────────────────
            if ($payment->transaction_type === 'payment' && $payment->invoice_id) {
                $payment->loadMissing('invoice');
                $invoice = $payment->invoice;

                $invoice->amount_paid = (float) $invoice->amount_paid + (float) $payment->amount;

                if ((float) $invoice->amount_paid >= (float) $invoice->total_amount) {
                    $invoice->status = 'paid';
                } elseif ((float) $invoice->amount_paid > 0) {
                    $invoice->status = 'partial';
                }

                $invoice->save();

                FinanceService::postTransaction(
                    reference: $payment,
                    event: 'payment_received',
                    entryDate: $payment->payment_date?->copy() ?? now(),
                    currency: $payment->currency,
                    memoEn: "Payment for invoice {$invoice->invoice_number}",
                    memoAr: "دفعة للفاتورة {$invoice->invoice_number}",
                    lines: [
                        [
                            'account_code'   => '1010',
                            'debit'          => (float) $payment->amount,
                            'credit'         => 0,
                            'description_en' => "Cash received for invoice {$invoice->invoice_number}",
                            'description_ar' => "استلام نقدية للفاتورة {$invoice->invoice_number}",
                        ],
                        [
                            'account_code'   => '1200',
                            'debit'          => 0,
                            'credit'         => (float) $payment->amount,
                            'description_en' => "A/R cleared for invoice {$invoice->invoice_number}",
                            'description_ar' => "تسوية ذمم مدينة للفاتورة {$invoice->invoice_number}",
                        ],
                    ],
                );

                return;
            }

            // ── Deposit logic ────────────────────────────────────────────────
            if ($payment->transaction_type === 'deposit') {
                $sourceLabel = match ($payment->source_type) {
                    'staff'  => 'Staff Deposit',
                    'public' => 'Public Deposit',
                    default  => 'Deposit',
                };

                $sourceLabelAr = match ($payment->source_type) {
                    'staff'  => 'إيداع موظف',
                    'public' => 'إيداع عام',
                    default  => 'إيداع',
                };

                // Determine the credit account: use deposit_account_id if set,
                // otherwise fall back to the default cash account (1010).
                $creditAccountId  = $payment->deposit_account_id;
                $creditAccountCode = $creditAccountId ? null : '1010';

                FinanceService::postTransaction(
                    reference: $payment,
                    event: 'deposit_received',
                    entryDate: $payment->payment_date?->copy() ?? now(),
                    currency: $payment->currency,
                    memoEn: "{$sourceLabel} — ref: {$payment->reference}",
                    memoAr: "{$sourceLabelAr} — مرجع: {$payment->reference}",
                    lines: [
                        [
                            // Debit Cash / Bank
                            'account_code'   => '1010',
                            'debit'          => (float) $payment->amount,
                            'credit'         => 0,
                            'description_en' => "{$sourceLabel} received",
                            'description_ar' => "استلام {$sourceLabelAr}",
                        ],
                        array_filter([
                            // Credit the designated deposit account (or unearned revenue)
                            'account_id'     => $creditAccountId,
                            'account_code'   => $creditAccountCode,
                            'debit'          => 0,
                            'credit'         => (float) $payment->amount,
                            'description_en' => "{$sourceLabel} credited",
                            'description_ar' => "قيد {$sourceLabelAr}",
                        ]),
                    ],
                );
            }
        });
    }
}
