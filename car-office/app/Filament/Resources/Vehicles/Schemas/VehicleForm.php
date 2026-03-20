<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('registration_number'),
                TextInput::make('make_model')
                    ->required(),
                TextInput::make('current_mileage')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('last_service_date'),
            ]);
    }
}
