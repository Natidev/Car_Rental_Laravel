<?php

namespace App\Filament\Resources\OfficeRentAgreements;

use App\Enums\AgreementStatus;
use App\Enums\UserRole;
use App\Enums\BranchStatus;
use App\Filament\Resources\OfficeRentAgreements\Pages\CreateOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\EditOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\ListOfficeRentAgreements;
use App\Models\OfficeRentAgreement;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

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
            FileUpload::make('scanned_contract_path')
                ->label('Scanned Contract')
                ->disk('public')
                ->directory('office-rent-agreements')
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->nullable(),
            Select::make('status')
                ->options([
                    AgreementStatus::DRAFT->value => 'draft',
                    AgreementStatus::UNDER_REVIEW->value => 'under_review',
                    AgreementStatus::ACTIVE->value => 'active',
                    AgreementStatus::EXPIRED->value => 'expired',
                    AgreementStatus::TERMINATED->value => 'terminated',
                    AgreementStatus::REJECTED->value => 'rejected',
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
                Action::make('submit_for_review')
                    ->label('Submit for Review')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (OfficeRentAgreement $record) => static::isAdmin() && $record->status === AgreementStatus::DRAFT && !empty($record->scanned_contract_path))
                    ->action(fn (OfficeRentAgreement $record) => $record->update(['status' => AgreementStatus::UNDER_REVIEW])),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (OfficeRentAgreement $record) => (static::isAdmin() || static::isLegal()) && $record->status === AgreementStatus::UNDER_REVIEW)
                    ->action(function (OfficeRentAgreement $record) {
                        $record->update([
                            'status' => AgreementStatus::ACTIVE,
                            'approved_at' => now(),
                            'approved_by' => Auth::id(),
                        ]);
                        // Activate the branch
                        $record->branch->update(['status' => BranchStatus::ACTIVE]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (OfficeRentAgreement $record) => (static::isAdmin() || static::isLegal()) && $record->status === AgreementStatus::UNDER_REVIEW)
                    ->action(fn (OfficeRentAgreement $record) => $record->update([
                        'status' => AgreementStatus::REJECTED,
                        'approved_at' => now(),
                        'approved_by' => Auth::id(),
                    ])),
                EditAction::make()
                    ->visible(fn () => static::isAdmin() || static::isLegal()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (! static::isAdmin() && ! static::isLegal()) {
            $query->where('status', '!=', AgreementStatus::DRAFT);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && (static::isAdmin() || static::isLegal());
    }

    public static function canCreate(): bool
    {
        return Auth::check() && static::isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && (static::isAdmin() || static::isLegal());
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && static::isAdmin();
    }

    private static function isLegal(): bool
    {
        return Auth::user()?->role === UserRole::LEGAL;
    }

    private static function isAdmin(): bool
    {
        return Auth::user()?->role === UserRole::ADMIN;
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

