<?php

namespace App\Filament\Resources\StaffReceivables\Pages;

use App\Filament\Resources\StaffReceivables\StaffReceivableResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStaffReceivable extends CreateRecord
{
    protected static string $resource = StaffReceivableResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure transaction_type is always one of the receivable types
        // (not 'salary') when created through this resource.
        if (! in_array($data['transaction_type'] ?? '', ['receivable', 'advance', 'collection'])) {
            $data['transaction_type'] = 'receivable';
        }

        return $data;
    }
}
