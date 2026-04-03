<?php

namespace App\Filament\Resources\LoyaltyPoints\Pages;

use App\Filament\Resources\LoyaltyPoints\LoyaltyPointsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyaltyPoints extends ListRecords
{
    protected static string $resource = LoyaltyPointsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
