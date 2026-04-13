<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 
        'customer_id', 
        'order_id', // Add order_id relationship
        'issue_date', 
        'due_date',
        'subtotal', 
        'tax_total',
        'tax_percentage',
        'total_amount',
        'amount_paid',
        'currency', 
        'status',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::created(function (self $invoice) {
    FinanceService::postTransaction(
        reference: $invoice,
        event: 'invoice_issued',
        entryDate: $invoice->issue_date ?? now(),
        currency: $invoice->currency,
        memoEn: "Invoice {$invoice->invoice_number}",
        lines: [
            [
                'account_code' => '1200', // Accounts Receivable (Asset)
                'debit' => (float) $invoice->total_amount,
                'credit' => 0,
            ],
            [
                'account_code' => '4000', // Sales Revenue (Income)
                'debit' => 0,
                'credit' => (float) $invoice->total_amount,
            ],
        ],
    );
});
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function refreshTotals()
    {
        $this->loadMissing('items.taxRate');
        
        $subtotal = (float) $this->items->sum('subtotal');

        $defaultTaxRate = (float) ($this->tax_percentage ?? 0);
        $taxTotal = 0.0;

        foreach ($this->items as $item) {
            $lineSubtotal = (float) $item->subtotal;
            $rate = $item->taxRate ? (float) $item->taxRate->rate : $defaultTaxRate;
            $taxTotal += $lineSubtotal * ($rate / 100);
        }

        $totalAmount = $subtotal + $taxTotal;

        $this->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total_amount' => $totalAmount,
        ]);
    }

    public function generateInvoiceNumber()
    {
        // Get the latest invoice number
        $latestInvoice = static::latest('id')->first();
        
        if (!$latestInvoice) {
            return 'INV-0001';
        }
        
        // Extract the number part and increment
        $latestNumber = intval(substr($latestInvoice->invoice_number, -4));
        $newNumber = $latestNumber + 1;
        
        return 'INV-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
