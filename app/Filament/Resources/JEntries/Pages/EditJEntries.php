<?php

namespace App\Filament\Resources\JEntries\Pages;

use App\Filament\Resources\JEntries\JEntriesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJEntries extends EditRecord
{
    protected static string $resource = JEntriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
