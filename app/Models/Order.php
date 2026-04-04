<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['product_id', 'quantity', 'total_price', 'order_date', 'customer_id', 'status'];

    protected $casts = [
        'order_date' => 'date',
        'quantity' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function hasInvoice()
    {
        return $this->invoices()->exists();
    }

    public function canBeConvertedToInvoice()
    {
        return $this->status !== 'cancelled' && !$this->hasInvoice();
    }
}
