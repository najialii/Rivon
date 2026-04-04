<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = ['invoice_id', 'description', 'quantity', 'unit_price', 'subtotal'];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::saved(function ($item) {
            if ($item->invoice) {
                $item->invoice->refreshTotals();
            }
        });

        static::deleted(function ($item) {
            if ($item->invoice) {
                $item->invoice->refreshTotals();
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 2);
    }
}