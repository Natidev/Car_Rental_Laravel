<?php

namespace App\Services;

use App\Models\OfficeRentAgreement;
use App\Notifications\OfficeRentAgreementCreatedNotification;

class AgreementCreatedNotificationService
{
    /**
     * @return bool True if a notification was sent, otherwise false.
     */
    public function sendCreatedNotification(OfficeRentAgreement $agreement): bool
    {
        $recipient = $agreement->approvedBy;

        if (! $recipient) {
            return false;
        }

        $recipient->notify(new OfficeRentAgreementCreatedNotification($agreement));

        return true;
    }
}

