<?php

namespace App\Models;

use App\Enums\AgreementStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeRentAgreement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'agreement_id',
        'landlord_name',
        'property_address',
        'monthly_rent',
        'payment_schedule',
        'start_date',
        'end_date',
        'scanned_contract_path',
        'status',
        'approved_at',
        'approved_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AgreementStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function getRemainingDurationAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        return Carbon::now()->diffInDays(Carbon::parse($this->end_date), false);
    }

    public function scopeExpiringSoon($query, int $days = 90)
    {
        $futureDate = Carbon::now()->addDays($days);

        return $query->whereNotNull('end_date')
            ->where('end_date', '<=', $futureDate)
            ->where('end_date', '>=', Carbon::now())
            ->where('status', '!=', AgreementStatus::EXPIRED);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function agreementRenewals(): HasMany
    {
        return $this->hasMany(AgreementRenewal::class);
    }
}
