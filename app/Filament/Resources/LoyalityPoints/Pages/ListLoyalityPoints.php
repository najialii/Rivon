<?php

namespace App\Filament\Resources\LoyalityPoints\Pages;

use App\Filament\Resources\LoyalityPoints\LoyalityPointsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyalityPoints extends ListRecords
{
    protected static string $resource = LoyalityPointsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
