<?php

namespace App\Filament\Resources\Costs\Pages;

use App\Filament\Resources\Costs\CostResource;
use App\Services\AccountingService;
use Filament\Resources\Pages\CreateRecord;

class CreateCost extends CreateRecord
{
    protected static string $resource = CostResource::class;

    protected function afterCreate(): void
    {
        $cost = $this->record;

        if (!$cost) {
            return;
        }

        $cost->refresh();
        AccountingService::postCost($cost);
    }
}
