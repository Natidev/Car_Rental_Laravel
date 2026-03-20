<?php

namespace App\Enums;

enum AgreementStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';
    case PENDING_APPROVAL = 'pending_approval';
    case RENEWED = 'renewed';
}
