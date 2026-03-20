<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{

    use HasFactory, Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin';
    }
    public function approvedOfficeRentAgreements(): HasMany
    {
        return $this->hasMany(OfficeRentAgreement::class, 'approved_by');
    }

    public function approvedAgreementRenewals(): HasMany
    {
        return $this->hasMany(AgreementRenewal::class, 'approved_by');
    }

    /**
     * Get the vehicle service requests requested by this user.
     */
    public function vehicleServiceRequests(): HasMany
    {
        return $this->hasMany(VehicleServiceRequest::class, 'requested_by');
    }

    /**
     * Get the utility payments made by this user.
     */
    public function utilityPayments(): HasMany
    {
        return $this->hasMany(UtilityPayment::class, 'paid_by');
    }
}
