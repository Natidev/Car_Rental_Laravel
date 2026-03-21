<?php

namespace App\Filament\Resources\VehicleServiceRequests\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\VehicleServiceRequests\VehicleServiceRequestResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleServiceRequest extends CreateRecord
{
    protected static string $resource = VehicleServiceRequestResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user instanceof User && $user->role === UserRole::STAFF) {
            $data['requested_by'] = $user->id;
            $data['status'] = 'pending';
        }

        return $data;
    }
}

