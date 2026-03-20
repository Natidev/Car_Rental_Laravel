<?php

namespace App\Filament\Resources\AgreementRenewals\Pages;

use App\Filament\Resources\AgreementRenewals\AgreementRenewalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAgreementRenewal extends EditRecord
{
    protected static string $resource = AgreementRenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

