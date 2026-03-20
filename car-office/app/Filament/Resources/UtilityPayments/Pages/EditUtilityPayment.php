<?php

namespace App\Filament\Resources\UtilityPayments\Pages;

use App\Filament\Resources\UtilityPayments\UtilityPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUtilityPayment extends EditRecord
{
    protected static string $resource = UtilityPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

