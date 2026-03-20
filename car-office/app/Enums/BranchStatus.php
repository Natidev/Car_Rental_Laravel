<?php

namespace App\Enums;

enum BranchStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case CLOSED = 'closed';
}
