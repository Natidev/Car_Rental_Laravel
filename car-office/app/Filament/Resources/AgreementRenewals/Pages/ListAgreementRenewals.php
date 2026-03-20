<?php

namespace App\Filament\Resources\AgreementRenewals\Pages;

use App\Filament\Resources\AgreementRenewals\AgreementRenewalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgreementRenewals extends ListRecords
{
    protected static string $resource = AgreementRenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

