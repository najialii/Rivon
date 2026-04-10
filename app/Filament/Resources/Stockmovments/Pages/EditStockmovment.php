<?php

namespace App\Filament\Resources\Stockmovments\Pages;

use App\Filament\Resources\Stockmovments\StockmovmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockmovment extends EditRecord
{
    protected static string $resource = StockmovmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
