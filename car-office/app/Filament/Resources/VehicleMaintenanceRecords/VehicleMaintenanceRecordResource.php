<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords;

use App\Filament\Resources\VehicleMaintenanceRecords\Pages\CreateVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\EditVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\ListVehicleMaintenanceRecords;
use App\Models\VehicleMaintenanceRecord;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleMaintenanceRecordResource extends Resource
{
    protected static ?string $model = VehicleMaintenanceRecord::class;

    protected static string|UnitEnum|null $navigationGroup = 'Vehicles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vehicle_service_request_id')
                ->relationship('vehicleServiceRequest', 'id')
                ->required(),
            Select::make('vehicle_id')
                ->relationship('vehicle', 'plate_number')
                ->required(),
            TextInput::make('service_provider')
                ->required(),
            TextInput::make('service_details')
                ->nullable(),
            TextInput::make('mileage_at_service')
                ->required()
                ->numeric(),
            DatePicker::make('completed_date')
                ->required(),
            TextInput::make('cost')
                ->nullable()
                ->numeric(),
            TextInput::make('report_path')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.plate_number')
                    ->searchable(),
                TextColumn::make('service_provider')
                    ->searchable(),
                TextColumn::make('mileage_at_service')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('completed_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('cost')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleMaintenanceRecords::route('/'),
            'create' => CreateVehicleMaintenanceRecord::route('/create'),
            'edit' => EditVehicleMaintenanceRecord::route('/{record}/edit'),
        ];
    }
}

