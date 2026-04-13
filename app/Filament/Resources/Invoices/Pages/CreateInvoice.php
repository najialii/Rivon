<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Services\AccountingService;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function afterCreate(): void
    {
        $invoice = $this->record;

        if (!$invoice) {
            return;
        }

        $invoice->refreshTotals();
        $invoice->refresh()->loadMissing('items');

        if (in_array($invoice->status, ['sent', 'paid'], true)) {
            AccountingService::postInvoiceIssue($invoice);
        }
    }
}
