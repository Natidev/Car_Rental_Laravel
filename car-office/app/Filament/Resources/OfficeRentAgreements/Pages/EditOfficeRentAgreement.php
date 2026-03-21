<?php

namespace App\Filament\Resources\OfficeRentAgreements\Pages;

use App\Filament\Resources\OfficeRentAgreements\OfficeRentAgreementResource;
use App\Services\FilamentCrudNotificationService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficeRentAgreement extends EditRecord
{
    protected static string $resource = OfficeRentAgreementResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return null;
    }

    protected function afterSave(): void
    {
        if (! $this->record) {
            return;
        }

        app(FilamentCrudNotificationService::class)->notifyUpdated($this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->successNotificationTitle(null)
                ->after(function (): void {
                    if (! $this->record) {
                        return;
                    }

                    app(FilamentCrudNotificationService::class)->notifyDeleted($this->record);
                }),
        ];
    }
}

