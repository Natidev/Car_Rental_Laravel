<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AgreementStats;
use App\Filament\Widgets\UsersOverview;
use App\Filament\Widgets\VehicleMaintenanceStats;
use App\Filament\Widgets\VehiclesDueMaintenanceTable;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AgreementStats::class,
            UsersOverview::class,
            VehicleMaintenanceStats::class,
            VehiclesDueMaintenanceTable::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}