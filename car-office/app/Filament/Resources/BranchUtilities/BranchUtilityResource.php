<?php

namespace App\Filament\Resources\BranchUtilities;

use App\Filament\Resources\BranchUtilities\Pages\CreateBranchUtility;
use App\Filament\Resources\BranchUtilities\Pages\EditBranchUtility;
use App\Filament\Resources\BranchUtilities\Pages\ListBranchUtilities;
use App\Models\BranchUtility;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BranchUtilityResource extends Resource
{
    protected static ?string $model = BranchUtility::class;

    protected static string|UnitEnum|null $navigationGroup = 'Utilities';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')
                ->relationship('branch', 'name')
                ->required(),
            Select::make('type')
                ->options([
                    'electricity' => 'electricity',
                    'water' => 'water',
                    'telephone' => 'telephone',
                    'internet' => 'internet',
                    'other' => 'other',
                ])
                ->required(),
            TextInput::make('provider')
                ->required(),
            TextInput::make('account_number')
                ->required(),
            TextInput::make('payment_cycle')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('account_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('payment_cycle')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranchUtilities::route('/'),
            'create' => CreateBranchUtility::route('/create'),
            'edit' => EditBranchUtility::route('/{record}/edit'),
        ];
    }
}

