<?php

namespace App\Filament\Resources\OfficeRentAgreements;

use App\Enums\AgreementStatus;
use App\Filament\Resources\OfficeRentAgreements\Pages\CreateOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\EditOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\ListOfficeRentAgreements;
use App\Models\OfficeRentAgreement;
use BackedEnum;
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

class OfficeRentAgreementResource extends Resource
{
    protected static ?string $model = OfficeRentAgreement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_id')
                ->relationship('branch', 'name')
                ->required(),
            TextInput::make('agreement_id')
                ->required(),
            TextInput::make('landlord_name')
                ->required(),
            TextInput::make('property_address')
                ->required(),
            TextInput::make('monthly_rent')
                ->required()
                ->numeric(),
            TextInput::make('payment_schedule')
                ->required(),
            DatePicker::make('start_date')
                ->required(),
            DatePicker::make('end_date')
                ->required(),
            TextInput::make('scanned_contract_path')
                ->nullable(),
            Select::make('status')
                ->options([
                    AgreementStatus::DRAFT->value => 'draft',
                    AgreementStatus::UNDER_REVIEW->value => 'under_review',
                    AgreementStatus::ACTIVE->value => 'active',
                    AgreementStatus::EXPIRED->value => 'expired',
                    AgreementStatus::TERMINATED->value => 'terminated',
                ])
                ->default(AgreementStatus::DRAFT->value)
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
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('agreement_id')
                    ->searchable(),
                TextColumn::make('landlord_name')
                    ->searchable(),
                TextColumn::make('monthly_rent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_schedule')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
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
                TextColumn::make('deleted_at')
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
            'index' => ListOfficeRentAgreements::route('/'),
            'create' => CreateOfficeRentAgreement::route('/create'),
            'edit' => EditOfficeRentAgreement::route('/{record}/edit'),
        ];
    }
}

