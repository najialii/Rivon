<?php

namespace App\Filament\Resources\Stockmovments\Pages;

use App\Filament\Resources\Stockmovments\StockmovmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockmovments extends ListRecords
{
    protected static string $resource = StockmovmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
