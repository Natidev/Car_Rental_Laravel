<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgreementRenewal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'office_rent_agreement_id',
        'new_monthly_rent',
        'new_start_date',
        'new_end_date',
        'amendment_notes',
        'status',
        'approved_at',
        'approved_by',
    ];

    public function officeRentAgreement(): BelongsTo
    {
        return $this->belongsTo(OfficeRentAgreement::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
