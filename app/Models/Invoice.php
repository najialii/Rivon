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
        'order_id', // Add order_id relationship
        'issue_date', 
        'due_date',
        'subtotal', 
        'tax_total',
        'tax_percentage',
        'total_amount',
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
    ];

    protected static function booted()
    {
        static::updated(function ($invoice) {
            // Create journal entry when invoice status changes to 'paid'
            if ($invoice->wasChanged('status') && $invoice->status === 'paid') {
                FinanceService::processTransaction(
                    reference: $invoice,
                    debitAccountId: 1, // Cash on Hand - you may want to make this configurable
                    creditAccountId: 2, // Sales Revenue - you may want to make this configurable
                    amount: $invoice->total_amount,
                    description: "Invoice #{$invoice->invoice_number} payment",
                    currency: $invoice->currency
                );
            }
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

    public function refreshTotals()
    {
        $this->load('items');
        
        $subtotal = $this->items->sum('subtotal');
        $taxTotal = $subtotal * ($this->tax_percentage / 100);
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
