<?php

namespace App\Listeners;

use App\Events\WorkflowSave;
use App\Notifications\WorkflowCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;

class NotifiWorkflowSave
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WorkflowSave $event): void
    {
        Notification::route('slack', env('SLACK_BOT_USER_DEFAULT_CHANNEL'))
        ->notify(new (WorkflowCreated::class));
    }
}
