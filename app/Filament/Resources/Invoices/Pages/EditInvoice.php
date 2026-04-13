<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use App\Services\AccountingService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function afterSave(): void
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
