<?php

namespace App\Filament\Resources\Reports\TrialBalance\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TrialBalanceTable
{
    public static function configure(Table $table): Table
    {
        $defaultFrom = now()->startOfMonth()->toDateString();
        $defaultTo = now()->toDateString();

        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->fontFamily('mono')
                    ->sortable(),

                TextColumn::make('name_en')
                    ->label('Account')
                    ->searchable(),

                TextColumn::make('account_type')
                    ->label('Type')
                    ->badge(),

                TextColumn::make('period_debits')
                    ->label('Debits')
                    ->alignEnd(),

                TextColumn::make('period_credits')
                    ->label('Credits')
                    ->alignEnd(),

                TextColumn::make('net')
                    ->label('Net')
                    ->state(function ($record) {
                        return (float) ($record->period_debits ?? 0) - (float) ($record->period_credits ?? 0);
                    })
                    ->alignEnd()
                    ->weight('bold'),
            ])
            ->filters([
                Filter::make('period')
                    ->form([
                        DatePicker::make('from')->default($defaultFrom),
                        DatePicker::make('to')->default($defaultTo),
                        Select::make('currency')
                            ->options([
                                'USD' => 'USD',
                                'SDG' => 'SDG',
                                'AED' => 'AED',
                                'EGP' => 'EGP',
                            ])
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $from = isset($data['from']) ? Carbon::parse($data['from'])->toDateString() : null;
                        $to = isset($data['to']) ? Carbon::parse($data['to'])->toDateString() : null;
                        $currency = $data['currency'] ?? null;

                        if ($from && $to) {
                            $dateExpr = DB::raw('COALESCE(journal_transactions.entry_date, DATE(jentries.created_at))');
                            $query->whereBetween($dateExpr, [$from, $to]);
                        }

                        if ($currency) {
                            $query->where('jentries.currency', $currency);
                        }
                    }),
            ])
            ->defaultSort('code')
            ->paginated([25, 50, 100]);
    }
}
