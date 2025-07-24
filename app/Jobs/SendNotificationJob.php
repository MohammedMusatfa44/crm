<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Notifications\Notification;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userIds;
    protected $notification;

    /**
     * Create a new job instance.
     */
    public function __construct($userIds, Notification $notification)
    {
        $this->userIds = $userIds;
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereIn('id', (array)$this->userIds)->get();
        foreach ($users as $user) {
            $user->notify($this->notification);
        }
    }
}
