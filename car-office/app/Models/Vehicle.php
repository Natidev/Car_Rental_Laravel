<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'plate_number',
        'registration_number',
        'make_model',
        'current_mileage',
        'last_service_date',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function vehicleLicenses(): HasMany
    {
        return $this->hasMany(VehicleLicense::class);
    }

    public function vehicleServiceRequests(): HasMany
    {
        return $this->hasMany(VehicleServiceRequest::class);
    }

    public function vehicleMaintenanceRecords(): HasMany
    {
        return $this->hasMany(VehicleMaintenanceRecord::class);
    }
}
