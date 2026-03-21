<?php

namespace App\Filament\Resources\VehicleServiceRequests;

use App\Enums\ServiceType;
use App\Enums\UserRole;
use App\Enums\UrgencyLevel;
use App\Filament\Resources\VehicleServiceRequests\Pages\CreateVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\EditVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\ListVehicleServiceRequests;
use App\Models\User;
use App\Models\VehicleServiceRequest;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleServiceRequestResource extends Resource
{
    protected static ?string $model = VehicleServiceRequest::class;

    protected static string|UnitEnum|null $navigationGroup = 'Vehicles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vehicle_id')
                ->relationship('vehicle', 'plate_number')
                ->required(),
            Select::make('requested_by')
                ->label('Requested by')
                ->relationship('requestedBy', 'name')
                ->default(fn () => Auth::id())
                ->hidden(fn () => Auth::user() instanceof User && Auth::user()->role === UserRole::STAFF)
                ->required(),
            Textarea::make('problem_description')
                ->required(),
            Select::make('service_type')
                ->options(ServiceType::class)
                ->required(),
            Select::make('urgency')
                ->options(UrgencyLevel::class)
                ->default(UrgencyLevel::MEDIUM->value)
                ->required(),
            Select::make('status')
                ->options([
                    'pending' => 'pending',
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'in_progress' => 'in_progress',
                    'completed' => 'completed',
                ])
                ->default('pending')
                ->hidden(fn () => Auth::user() instanceof User && Auth::user()->role === UserRole::STAFF)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.plate_number')
                    ->searchable(),
                TextColumn::make('requestedBy.name')
                    ->label('Requested by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('problem_description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('urgency')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
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
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (VehicleServiceRequest $record) => static::isAdmin() && $record->status?->value === 'pending')
                    ->action(fn (VehicleServiceRequest $record) => $record->update(['status' => 'approved'])),
                Action::make('deny')
                    ->label('Deny')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (VehicleServiceRequest $record) => static::isAdmin() && $record->status?->value === 'pending')
                    ->action(fn (VehicleServiceRequest $record) => $record->update(['status' => 'rejected'])),
                EditAction::make()
                    ->visible(fn () => static::isAdmin()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => static::isAdmin()),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! static::isAdmin() && Auth::id()) {
            $query->where('requested_by', Auth::id());
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return Auth::check();
    }

    public static function canCreate(): bool
    {
        return Auth::user() instanceof User;
    }

    public static function canEdit(Model $record): bool
    {
        return static::isAdmin();
    }

    public static function canDelete(Model $record): bool
    {
        return static::isAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return static::isAdmin();
    }

    private static function isAdmin(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === UserRole::ADMIN;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleServiceRequests::route('/'),
            'create' => CreateVehicleServiceRequest::route('/create'),
            'edit' => EditVehicleServiceRequest::route('/{record}/edit'),
        ];
    }
}

