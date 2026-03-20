<?php

namespace App\Enums;

enum UtilityType: string
{
    case ELECTRICITY = 'electricity';
    case WATER = 'water';
    case INTERNET = 'internet';
    case TELEPHONE = 'telephone';
    case GAS = 'gas';
    case SECURITY = 'security';
    case WASTE_MANAGEMENT = 'waste_management';
    case OTHER = 'other';
}
