<?php

namespace App\Models;

use App\Enums\BranchStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Branch extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'proposed_office',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BranchStatus::class,
        ];
    }

    public function officeRentAgreements(): HasMany
    {
        return $this->hasMany(OfficeRentAgreement::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function branchUtilities(): HasMany
    {
        return $this->hasMany(BranchUtility::class);
    }

    public function utilityPayments(): HasManyThrough
    {
        return $this->hasManyThrough(UtilityPayment::class, BranchUtility::class);
    }
}
