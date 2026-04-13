<?php

namespace App\Filament\Resources\Reports\TrialBalance\Pages;

use App\Filament\Resources\Reports\TrialBalance\TrialBalanceResource;
use Filament\Resources\Pages\ListRecords;

class ListTrialBalances extends ListRecords
{
    protected static string $resource = TrialBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

