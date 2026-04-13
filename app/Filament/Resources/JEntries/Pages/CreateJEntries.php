<?php

namespace App\Filament\Resources\JEntries\Pages;

use App\Filament\Resources\JEntries\JEntriesResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateJEntries extends CreateRecord
{
    protected static string $resource = JEntriesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lines = $data['lines'] ?? [];

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($lines as $line) {
            $totalDebit += (float) ($line['debit'] ?? 0);
            $totalCredit += (float) ($line['credit'] ?? 0);
        }

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw ValidationException::withMessages([
                'lines' => 'Debit and credit totals must be equal.',
            ]);
        }

        return $data;
    }
}
