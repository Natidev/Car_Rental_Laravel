<?php

namespace App\Filament\Resources\VehicleLicenses\Pages;

use App\Filament\Resources\VehicleLicenses\VehicleLicenseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleLicense extends EditRecord
{
    protected static string $resource = VehicleLicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

