<?php

namespace App\Filament\Resources\BranchUtilities\Pages;

use App\Filament\Resources\BranchUtilities\BranchUtilityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBranchUtilities extends ListRecords
{
    protected static string $resource = BranchUtilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

