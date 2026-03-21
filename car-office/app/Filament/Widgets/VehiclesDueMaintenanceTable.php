<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class VehiclesDueMaintenanceTable extends TableWidget
{
    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Vehicles Due for Maintenance')
            ->query(
                Vehicle::query()
                    ->where(function (Builder $query): void {
                        $query
                            ->whereNull('last_service_date')
                            ->orWhereDate('last_service_date', '<=', now()->subMonths(6)->toDateString());
                    }),
            )
            ->columns([
                TextColumn::make('plate_number')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable(),
                TextColumn::make('current_mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('last_service_date')
                    ->date()
                    ->placeholder('Never serviced')
                    ->sortable(),
                TextColumn::make('days_since_last_service')
                    ->label('Days Since Last Service')
                    ->state(function (Vehicle $record): string {
                        if (! $record->last_service_date) {
                            return 'N/A';
                        }

                        return (string) Carbon::parse($record->last_service_date)->diffInDays(now());
                    }),
            ]);
    }
}

