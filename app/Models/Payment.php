<?php

namespace App\Models;

use App\Services\FinanceService;
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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function (self $payment) {
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
                        'account_code' => '1010',
                        'debit' => (float) $payment->amount,
                        'credit' => 0,
                        'description_en' => "Cash received for invoice {$invoice->invoice_number}",
                        'description_ar' => "استلام نقدية للفاتورة {$invoice->invoice_number}",
                    ],
                    [
                        'account_code' => '1200',
                        'debit' => 0,
                        'credit' => (float) $payment->amount,
                        'description_en' => "A/R cleared for invoice {$invoice->invoice_number}",
                        'description_ar' => "تسوية ذمم مدينة للفاتورة {$invoice->invoice_number}",
                    ],
                ],
            );
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

