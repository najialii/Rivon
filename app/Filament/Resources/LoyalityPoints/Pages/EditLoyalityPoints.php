<?php

namespace App\Filament\Resources\LoyalityPoints\Pages;

use App\Filament\Resources\LoyalityPoints\LoyalityPointsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyalityPoints extends EditRecord
{
    protected static string $resource = LoyalityPointsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
