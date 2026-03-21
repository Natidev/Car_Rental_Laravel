<?php

namespace App\Notifications;

use App\Models\OfficeRentAgreement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OfficeRentAgreementCreatedNotification extends Notification
{
    use Queueable;

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
        $endDate = $this->agreement->end_date ? (string) $this->agreement->end_date : 'N/A';
        $status = $this->agreement->status ?? 'N/A';

        return (new MailMessage())
            ->subject('New office rent agreement created')
            ->greeting('Hello!')
            ->line("A new office rent agreement has been created.")
            ->line("Agreement ID: {$this->agreement->agreement_id}")
            ->line("Branch: {$branchName}")
            ->line("End Date: {$endDate}")
            ->line("Status: {$status}")
            ->line('Please review it in the admin panel.')
            ->salutation('Car Office');
    }
}

