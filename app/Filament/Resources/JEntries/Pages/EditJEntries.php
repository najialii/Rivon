<?php

namespace App\Filament\Resources\JEntries\Pages;

use App\Filament\Resources\JEntries\JEntriesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Services\FinanceService;
use Illuminate\Validation\ValidationException;

class EditJEntries extends EditRecord
{
    protected static string $resource = JEntriesResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reverse')
                ->label('Reverse')
                ->color('danger')
                ->requiresConfirmation()
                ->disabled(fn () => !$this->record?->isPosted())
                ->action(function () {
                    FinanceService::reverseTransaction($this->record, auth()->id());
                    Notification::make()
                        ->title('Reversal posted')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
