<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;

class InvoiceService
{
    public static function convertOrderToInvoice(Order $order): Invoice
    {
        $order->loadMissing('order_items.product');

        $invoice = Invoice::create([
            'invoice_number' => (new Invoice())->generateInvoiceNumber(),
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(30), // 30 days due
            'tax_percentage' => 10, // Default 10% tax - you may want to make this configurable
            'currency' => 'USD', // Default currency - you may want to make this configurable
            'status' => 'draft',
            'notes' => "Generated from Order #{$order->id}",
        ]);

        foreach ($order->order_items as $orderItem) {
            $quantity = (float) $orderItem->quantity;
            $unitPrice = $quantity > 0 ? ((float) $orderItem->price) : 0;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $orderItem->product?->name_en ?? "Product #{$orderItem->product_id}",
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $quantity * $unitPrice,
            ]);
        }

        $invoice->refreshTotals();

        return $invoice;
    }

    public static function updateInvoiceFromOrder(Invoice $invoice, Order $order): void
    {
        $order->loadMissing('order_items.product');

        $invoice->update([
            'customer_id' => $order->customer_id,
            'notes' => "Updated from Order #{$order->id}",
        ]);

        $invoice->items()->delete();

        foreach ($order->order_items as $orderItem) {
            $quantity = (float) $orderItem->quantity;
            $unitPrice = $quantity > 0 ? ((float) $orderItem->price) : 0;

            $invoice->items()->create([
                'description' => $orderItem->product?->name_en ?? "Product #{$orderItem->product_id}",
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $quantity * $unitPrice,
            ]);
        }

        $invoice->refreshTotals();
    }
}
