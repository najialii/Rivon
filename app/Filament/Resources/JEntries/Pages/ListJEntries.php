<?php

namespace App\Filament\Resources\JEntries\Pages;

use App\Filament\Resources\JEntries\JEntriesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJEntries extends ListRecords
{
    protected static string $resource = JEntriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
