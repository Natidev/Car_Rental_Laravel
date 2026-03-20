<?php

namespace App\Filament\Resources\UtilityPayments\Pages;

use App\Filament\Resources\UtilityPayments\UtilityPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUtilityPayments extends ListRecords
{
    protected static string $resource = UtilityPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

