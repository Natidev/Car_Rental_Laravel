<?php

namespace App\Filament\Resources\BranchUtilities\Pages;

use App\Filament\Resources\BranchUtilities\BranchUtilityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBranchUtility extends EditRecord
{
    protected static string $resource = BranchUtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

