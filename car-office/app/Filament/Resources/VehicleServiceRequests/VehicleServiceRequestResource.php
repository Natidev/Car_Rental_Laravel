<?php

namespace App\Filament\Resources\VehicleServiceRequests;

use App\Enums\ServiceType;
use App\Enums\UrgencyLevel;
use App\Filament\Resources\VehicleServiceRequests\Pages\CreateVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\EditVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\ListVehicleServiceRequests;
use App\Models\VehicleServiceRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleServiceRequestResource extends Resource
{
    protected static ?string $model = VehicleServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vehicle_id')
                ->relationship('vehicle', 'plate_number')
                ->required(),
            Select::make('requested_by')
                ->label('Requested by')
                ->relationship('requestedBy', 'name')
                ->required(),
            Textarea::make('problem_description')
                ->required(),
            Select::make('service_type')
                ->options(ServiceType::class)
                ->required(),
            Select::make('urgency')
                ->options(UrgencyLevel::class)
                ->default(UrgencyLevel::MEDIUM->value)
                ->required(),
            Select::make('status')
                ->options([
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'in_progress' => 'in_progress',
                    'completed' => 'completed',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.plate_number')
                    ->searchable(),
                TextColumn::make('requestedBy.name')
                    ->label('Requested by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('problem_description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('urgency')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
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
            'index' => ListVehicleServiceRequests::route('/'),
            'create' => CreateVehicleServiceRequest::route('/create'),
            'edit' => EditVehicleServiceRequest::route('/{record}/edit'),
        ];
    }
}

