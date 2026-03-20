<?php

namespace App\Filament\Resources\OfficeRentAgreements\Pages;

use App\Filament\Resources\OfficeRentAgreements\OfficeRentAgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficeRentAgreements extends ListRecords
{
    protected static string $resource = OfficeRentAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

