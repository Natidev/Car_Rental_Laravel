<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_utility_id',
        'amount',
        'billing_period_start',
        'billing_period_end',
        'due_date',
        'paid_date',
        'status',
        'receipt_path',
        'paid_by',
    ];

    public function branchUtility(): BelongsTo
    {
        return $this->belongsTo(BranchUtility::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
