<?php

namespace App\Services;

use App\Models\OfficeRentAgreement;
use App\Notifications\ContractExpiryNotification;

class AgreementExpiryNotificationService
{
    /**
     * @return bool True if a notification was sent, otherwise false.
     */
    public function sendExpiryReminder(OfficeRentAgreement $agreement): bool
    {
        $recipient = $agreement->approvedBy;

        if (! $recipient) {
            return false;
        }

        $recipient->notify(new ContractExpiryNotification($agreement));

        return true;
    }
}

