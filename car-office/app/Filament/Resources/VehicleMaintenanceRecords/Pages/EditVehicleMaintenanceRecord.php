<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords\Pages;

use App\Filament\Resources\VehicleMaintenanceRecords\VehicleMaintenanceRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleMaintenanceRecord extends EditRecord
{
    protected static string $resource = VehicleMaintenanceRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

