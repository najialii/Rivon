<?php
namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action; // Import the generic Action class
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add the POS Launch Button
            // Action::make('open_pos')
            //     ->label('Open POS Terminal')
            //     ->icon('heroicon-o-computer-desktop')
            //     ->color('success') // Makes it stand out as a green button
            //     ->url(fn (): string => POS::getUrl()), 

            CreateAction::make()
                ->label('New Manual Order'),
        ];
    }
}