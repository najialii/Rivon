<?php

namespace App\Filament\Resources\StaffReceivables\Pages;

use App\Filament\Resources\StaffReceivables\StaffReceivableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStaffReceivable extends EditRecord
{
    protected static string $resource = StaffReceivableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
