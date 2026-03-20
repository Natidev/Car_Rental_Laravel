<?php

namespace App\Filament\Resources\VehicleServiceRequests\Pages;

use App\Filament\Resources\VehicleServiceRequests\VehicleServiceRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleServiceRequest extends EditRecord
{
    protected static string $resource = VehicleServiceRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

