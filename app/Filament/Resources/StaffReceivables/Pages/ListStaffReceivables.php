<?php

namespace App\Filament\Resources\StaffReceivables\Pages;

use App\Filament\Resources\StaffReceivables\StaffReceivableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStaffReceivables extends ListRecords
{
    protected static string $resource = StaffReceivableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
