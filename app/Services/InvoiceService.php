<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;

class InvoiceService
{
    public static function convertOrderToInvoice(Order $order): Invoice
    {
        // Create invoice from order
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

        // Create invoice item from order
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => $order->product->name ?? 'Product',
            'quantity' => $order->quantity,
            'unit_price' => $order->total_price / $order->quantity,
            'subtotal' => $order->total_price,
        ]);

        // Refresh totals to calculate subtotal, tax, and total
        $invoice->refreshTotals();

        return $invoice;
    }

    public static function updateInvoiceFromOrder(Invoice $invoice, Order $order): void
    {
        // Update invoice basic info
        $invoice->update([
            'customer_id' => $order->customer_id,
            'notes' => "Updated from Order #{$order->id}",
        ]);

        // Update or create invoice item
        $invoiceItem = $invoice->items()->first();
        if ($invoiceItem) {
            $invoiceItem->update([
                'description' => $order->product->name ?? 'Product',
                'quantity' => $order->quantity,
                'unit_price' => $order->total_price / $order->quantity,
            ]);
        }

        // Refresh totals
        $invoice->refreshTotals();
    }
}
