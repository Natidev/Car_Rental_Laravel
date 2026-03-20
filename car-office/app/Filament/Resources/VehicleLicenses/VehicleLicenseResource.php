<?php

namespace App\Filament\Resources\VehicleLicenses;

use App\Filament\Resources\VehicleLicenses\Pages\CreateVehicleLicense;
use App\Filament\Resources\VehicleLicenses\Pages\EditVehicleLicense;
use App\Filament\Resources\VehicleLicenses\Pages\ListVehicleLicenses;
use App\Models\VehicleLicense;
use BackedEnum;
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

class VehicleLicenseResource extends Resource
{
    protected static ?string $model = VehicleLicense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vehicle_id')
                ->relationship('vehicle', 'plate_number')
                ->required(),
            DatePicker::make('bolo_expiry_date')
                ->nullable(),
            DatePicker::make('inspection_expiry_date')
                ->nullable(),
            TextInput::make('bolo_receipt_path')
                ->nullable(),
            TextInput::make('inspection_certificate_path')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.plate_number')
                    ->searchable(),
                TextColumn::make('bolo_expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('inspection_expiry_date')
                    ->date()
                    ->sortable(),
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
            'index' => ListVehicleLicenses::route('/'),
            'create' => CreateVehicleLicense::route('/create'),
            'edit' => EditVehicleLicense::route('/{record}/edit'),
        ];
    }
}

