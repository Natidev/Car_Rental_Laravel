<?php

namespace App\Filament\Widgets;

use App\Models\AgreementRenewal;
use App\Models\OfficeRentAgreement;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AgreementStats extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 1;

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $expiringAgreements = OfficeRentAgreement::expiringSoon()->count();

        $pendingRenewals = AgreementRenewal::where('status', 'draft')->count();

        return [
            Stat::make('Agreements Expiring Soon', $expiringAgreements)
                ->description('Agreements expiring within 90 days')
                ->color('danger'),
            Stat::make('Pending Renewals', $pendingRenewals)
                ->description('Agreement renewals awaiting approval')
                ->color('info'),
        ];
    }
}