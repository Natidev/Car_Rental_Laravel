<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use App\Services\FilamentCrudNotificationService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicle extends EditRecord
{
    protected static string $resource = VehicleResource::class;

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
