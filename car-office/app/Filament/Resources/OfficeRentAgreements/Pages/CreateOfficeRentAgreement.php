<?php

namespace App\Filament\Resources\OfficeRentAgreements\Pages;

use App\Filament\Resources\OfficeRentAgreements\OfficeRentAgreementResource;
use App\Services\AgreementCreatedNotificationService;
use App\Services\FilamentCrudNotificationService;
use Filament\Resources\Pages\CreateRecord;

class CreateOfficeRentAgreement extends CreateRecord
{
    protected static string $resource = OfficeRentAgreementResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return null;
    }

    protected function afterCreate(): void
    {
        if (! $this->record) {
            return;
        }

        app(FilamentCrudNotificationService::class)->notifyCreated($this->record);
        app(AgreementCreatedNotificationService::class)->sendCreatedNotification($this->record);
    }
}

