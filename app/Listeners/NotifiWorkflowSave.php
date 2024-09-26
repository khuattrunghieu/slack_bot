<?php

namespace App\Listeners;

use App\Events\WorkflowSave;
use App\Notifications\WorkflowCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;
use Illuminate\Support\Facades\Auth;

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
        $channelName = $event->workflow->channel;
        $user = Auth::user();
        Notification::send($user, new WorkflowCreated($channelName));
    }

}
