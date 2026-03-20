<?php

namespace App\Filament\Resources\UtilityPayments;

use App\Filament\Resources\UtilityPayments\Pages\CreateUtilityPayment;
use App\Filament\Resources\UtilityPayments\Pages\EditUtilityPayment;
use App\Filament\Resources\UtilityPayments\Pages\ListUtilityPayments;
use App\Models\UtilityPayment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UtilityPaymentResource extends Resource
{
    protected static ?string $model = UtilityPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('branch_utility_id')
                ->relationship('branchUtility', 'provider')
                ->required(),
            TextInput::make('amount')
                ->required()
                ->numeric(),
            DatePicker::make('billing_period_start')
                ->required(),
            DatePicker::make('billing_period_end')
                ->required(),
            DatePicker::make('due_date')
                ->required(),
            DatePicker::make('paid_date')
                ->nullable(),
            Select::make('status')
                ->options([
                    'pending' => 'pending',
                    'paid' => 'paid',
                    'overdue' => 'overdue',
                    'waived' => 'waived',
                ])
                ->default('pending')
                ->required(),
            TextInput::make('receipt_path')
                ->nullable(),
            Select::make('paid_by')
                ->label('Paid by')
                ->relationship('paidBy', 'name')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branchUtility.branch.name')
                    ->label('Branch')
                    ->searchable(),
                TextColumn::make('branchUtility.type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('branchUtility.provider')
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('billing_period_start')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('billing_period_end')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('paid_date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('paidBy.name')
                    ->label('Paid by')
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => ListUtilityPayments::route('/'),
            'create' => CreateUtilityPayment::route('/create'),
            'edit' => EditUtilityPayment::route('/{record}/edit'),
        ];
    }
}

