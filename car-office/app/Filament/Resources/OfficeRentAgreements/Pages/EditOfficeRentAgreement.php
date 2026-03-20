<?php

namespace App\Filament\Resources\OfficeRentAgreements\Pages;

use App\Filament\Resources\OfficeRentAgreements\OfficeRentAgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficeRentAgreement extends EditRecord
{
    protected static string $resource = OfficeRentAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

