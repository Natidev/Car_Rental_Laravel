<?php

namespace App\Filament\Resources\AgreementRenewals;

use App\Filament\Resources\AgreementRenewals\Pages\CreateAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\EditAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\ListAgreementRenewals;
use App\Models\AgreementRenewal;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AgreementRenewalResource extends Resource
{
    protected static ?string $model = AgreementRenewal::class;

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('office_rent_agreement_id')
                ->relationship('officeRentAgreement', 'agreement_id')
                ->required(),
            TextInput::make('new_monthly_rent')
                ->required()
                ->numeric(),
            DatePicker::make('new_start_date')
                ->required(),
            DatePicker::make('new_end_date')
                ->required(),
            TextInput::make('amendment_notes')
                ->nullable(),
            Select::make('status')
                ->options([
                    'draft' => 'draft',
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'active' => 'active',
                ])
                ->default('draft')
                ->required(),
            DateTimePicker::make('approved_at')
                ->nullable(),
            Select::make('approved_by')
                ->label('Approved by')
                ->relationship('approvedBy', 'name')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('officeRentAgreement.agreement_id')
                    ->searchable(),
                TextColumn::make('new_monthly_rent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('new_start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('new_end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('amendment_notes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('approvedBy.name')
                    ->label('Approved by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ListAgreementRenewals::route('/'),
            'create' => CreateAgreementRenewal::route('/create'),
            'edit' => EditAgreementRenewal::route('/{record}/edit'),
        ];
    }
}

