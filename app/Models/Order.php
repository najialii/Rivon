<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [ 'quantity', 'total_price', 'order_date', 'customer_id', 'status'];

    protected $casts = [
        'order_date' => 'date',
        'total_price' => 'decimal:2',
        'sale_type',
        'source',
        'otype',
        'staus'
        // 'cost'
    ];


    //add source an saletype 
  

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function hasInvoice()
    {
        return $this->invoices()->exists();
    }


    public function supplier(): BelongsTo 
    {
        return $this->belongsTo(supplier::class);
    }

    public function canBeConvertedToInvoice()
    {
        return $this->status !== 'cancelled' && !$this->hasInvoice();
    }



    
  public function order_items(): HasMany
    {
        return $this->hasMany(Order_item::class, 'order_id');
    }
}
