<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchUtility extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'type',
        'provider',
        'account_number',
        'payment_cycle',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function utilityPayments(): HasMany
    {
        return $this->hasMany(UtilityPayment::class);
    }
}
