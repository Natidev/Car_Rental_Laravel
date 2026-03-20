<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords\Pages;

use App\Filament\Resources\VehicleMaintenanceRecords\VehicleMaintenanceRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleMaintenanceRecords extends ListRecords
{
    protected static string $resource = VehicleMaintenanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

