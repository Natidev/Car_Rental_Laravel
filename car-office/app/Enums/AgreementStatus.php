<?php

namespace App\Enums;

enum AgreementStatus: string
{
    case DRAFT = 'draft';
    case UNDER_REVIEW = 'under_review';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';
    case PENDING_APPROVAL = 'pending_approval';
    case RENEWED = 'renewed';
}
