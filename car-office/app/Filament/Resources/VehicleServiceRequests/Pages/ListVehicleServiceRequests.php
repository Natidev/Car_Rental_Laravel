<?php

namespace App\Filament\Resources\VehicleServiceRequests\Pages;

use App\Filament\Resources\VehicleServiceRequests\VehicleServiceRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleServiceRequests extends ListRecords
{
    protected static string $resource = VehicleServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

