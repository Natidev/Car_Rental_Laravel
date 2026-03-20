<?php

namespace App\Filament\Resources\VehicleLicenses\Pages;

use App\Filament\Resources\VehicleLicenses\VehicleLicenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleLicenses extends ListRecords
{
    protected static string $resource = VehicleLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

