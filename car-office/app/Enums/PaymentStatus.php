<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';
    case PARTIAL = 'partial';
}
