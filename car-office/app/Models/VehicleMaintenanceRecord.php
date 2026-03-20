<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenanceRecord extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_service_request_id',
        'vehicle_id',
        'service_provider',
        'service_details',
        'mileage_at_service',
        'completed_date',
        'cost',
        'report_path',
    ];

    public function vehicleServiceRequest(): BelongsTo
    {
        return $this->belongsTo(VehicleServiceRequest::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
