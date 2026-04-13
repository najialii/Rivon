<?php

namespace App\Filament\Resources\Costs\Pages;

use App\Filament\Resources\Costs\CostResource;
use App\Services\AccountingService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCost extends EditRecord
{
    protected static string $resource = CostResource::class;

    protected function afterSave(): void
    {
        $cost = $this->record;

        if (!$cost) {
            return;
        }

        $cost->refresh();
        AccountingService::postCost($cost);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
