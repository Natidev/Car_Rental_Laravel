<?php

namespace App\Enums;

enum ServiceType: string
{
    case ROUTINE = 'routine';
    case REPAIR = 'repair';
    case INSPECTION = 'inspection';
    case OIL_CHANGE = 'oil_change';
    case TIRE_ROTATION = 'tire_rotation';
    case BRAKE_SERVICE = 'brake_service';
    case ENGINE = 'engine';
    case TRANSMISSION = 'transmission';
    case ELECTRICAL = 'electrical';
    case OTHER = 'other';
}
