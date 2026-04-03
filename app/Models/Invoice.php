<?php

namespace App\Models;

use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 
        'customer_id', 
        'total_amount', 
        'currency', 
        'status', 
        'issue_date', 
        'due_date',
        'notes'
    ];

    /**
     * Relationship: An invoice belongs to a Customer (User)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relationship: An invoice has many line items
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * The Accounting Logic:
     * When an invoice is marked as 'paid', it creates a Journal Entry automatically.
     */
    protected static function booted()
    {
        static::updated(function ($invoice) {
            // Only trigger if the status was changed to 'paid'
            if ($invoice->wasChanged('status') && $invoice->status === 'paid') {
                
                $salesAccount = \App\Models\Account::where('code', 'sales_revenue')->first();
                $cashAccount = \App\Models\Account::where('code', 'cash_on_hand')->first();

                if ($salesAccount && $cashAccount) {
                    FinanceService::processTransaction(
                        reference: $invoice,
                        debitAccountId: $cashAccount->id,      // Money enters the Bank/Cash
                        creditAccountId: $salesAccount->id,    // Revenue increases
                        amount: $invoice->total_amount,
                        descEn: "Payment received for Invoice #" . ($invoice->invoice_number ?? $invoice->id),
                        descAr: "تحصيل فاتورة رقم: " . ($invoice->invoice_number ?? $invoice->id),
                        currency: $invoice->currency
                    );
                } else {
                    \Log::error("Invoice Finance Error: Required accounts (sales_revenue/cash_on_hand) missing.");
                }
            }
        });
    }
}