<?php

namespace App\Filament\Resources\Branches\Schemas;

use App\Enums\BranchStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('proposed_office'),
                Select::make('status')
                    ->options(BranchStatus::class)
                    ->default('pending')
                    ->required(),
            ]);
    }
}
