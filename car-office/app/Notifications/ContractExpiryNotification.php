<?php

namespace App\Notifications;

use App\Models\OfficeRentAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContractExpiryNotification extends Notification
{
    use Queueable;

    /**
     * @param  OfficeRentAgreement  $agreement
     */
    public function __construct(
        public readonly OfficeRentAgreement $agreement,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $branchName = $this->agreement->branch?->name ?? 'Unknown branch';
        $remainingDays = $this->agreement->remainingDuration;

        $remainingDaysText = $remainingDays === null
            ? 'soon'
            : (string) $remainingDays . ' day(s)';

        return (new MailMessage())
            ->subject('Contract expiry reminder')
            ->greeting('Hello!')
            ->line("This is a reminder that your office rent agreement ({$this->agreement->agreement_id}) is expiring in {$remainingDaysText}.")
            ->line("Branch: {$branchName}")
            ->line('Please review and take appropriate action.')
            ->salutation('Car Office');
    }
}

