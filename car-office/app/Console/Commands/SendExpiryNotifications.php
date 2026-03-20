<?php

namespace App\Console\Commands;

use App\Services\AgreementExpiryNotificationService;
use App\Services\ExpiringAgreementsFinder;
use Illuminate\Console\Command;

class SendExpiryNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:expiry 
                            {--days=90 : Number of days to check for expiring agreements}
                            {--dry-run : Run without sending actual notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for expiring agreements and contracts';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $notificationService = app(AgreementExpiryNotificationService::class);
        $finder = app(ExpiringAgreementsFinder::class);

        $this->info("Checking for agreements expiring within {$days} days...");

        // Get agreements expiring within the specified days
        $expiringAgreements = $finder->getExpiringSoonAgreements($days);

        if ($expiringAgreements->isEmpty()) {
            $this->info('No expiring agreements found.');
            return Command::SUCCESS;
        }

        $this->info("Found {$expiringAgreements->count()} expiring agreement(s).");

        foreach ($expiringAgreements as $agreement) {
            $remainingDays = $agreement->remainingDuration;
            $this->line("Agreement: {$agreement->agreement_id}");
            $this->line("  Branch: {$agreement->branch->name}");
            $this->line("  End Date: {$agreement->end_date}");
            $this->line("  Remaining Days: {$remainingDays}");

            if (!$dryRun) {
                $sent = $notificationService->sendExpiryReminder($agreement);

                if ($sent) {
                    $this->line('  Notification sent.');
                } else {
                    $this->line('  Notification skipped (no approver found).');
                }
            } {
                $this->line('  [DRY RUN] Notification would be sent.');
            }

            $this->line('');
        }

        $message = $dryRun 
            ? 'Dry run complete. No notifications were sent.' 
            : 'Expiry notifications processed successfully.';

        $this->info($message);

        return Command::SUCCESS;
    }
}
