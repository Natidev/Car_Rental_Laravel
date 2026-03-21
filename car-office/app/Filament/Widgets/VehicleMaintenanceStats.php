<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VehicleMaintenanceStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 1;

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $vehiclesNeedingMaintenance = Vehicle::whereHas('vehicleServiceRequests', function ($query) {
            $query->whereIn('status', ['pending', 'approved']);
        })->count();

        return [
            Stat::make('Vehicles Needing Maintenance', $vehiclesNeedingMaintenance)
                ->description('Vehicles with pending or approved service requests')
                ->color('warning'),
        ];
    }
}