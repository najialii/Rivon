<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                ->label('warehouse name')
                ->required(),

 TextInput::make('location')
 ->label('location')
 ->required(),





            ]);
    }
}
