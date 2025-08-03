<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for notifications that need to be triggered';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for notifications that need to be triggered...');

        // Get notifications that need to be triggered
        $notifications = Notification::where('remind_at', '<=', now())
            ->where('is_triggered', false)
            ->where('is_read', false)
            ->with(['user', 'customer'])
            ->get();

        $triggeredCount = 0;

        foreach ($notifications as $notification) {
            // Mark as triggered
            $notification->update(['is_triggered' => true]);

            $this->info("Triggered notification: {$notification->title} for user: {$notification->user->name}");
            $triggeredCount++;
        }

        $this->info("Total notifications triggered: {$triggeredCount}");

        return 0;
    }
}
