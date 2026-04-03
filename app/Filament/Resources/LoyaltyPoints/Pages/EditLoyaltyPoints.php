<?php

namespace App\Filament\Resources\LoyaltyPoints\Pages;

use App\Filament\Resources\LoyaltyPoints\LoyaltyPointsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyaltyPoints extends EditRecord
{
    protected static string $resource = LoyaltyPointsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
