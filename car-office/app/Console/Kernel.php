<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run expiry notifications daily at 8:00 AM
        $schedule->command('notifications:expiry --days=90')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/expiry-notifications.log'));

        // Run 60-day check weekly
        $schedule->command('notifications:expiry --days=60')
            ->weekly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/expiry-notifications.log'));

        // Run 30-day check twice a week
        $schedule->command('notifications:expiry --days=30')
            ->twiceWeekly(1, 13)
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/expiry-notifications.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
