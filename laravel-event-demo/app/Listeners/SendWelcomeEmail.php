<?php

namespace App\Listeners;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserRegistered;
use App\Jobs\SendWelcomeEmailJob;
use App\Jobs\GiveWelcomePointJob;
class SendWelcomeEmail
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
    public function handle(UserRegistered $event): void
    {
        //
        Log::info('Welcome email sent to: ' . $event->user->email);
        SendWelcomeEmailJob::dispatch($event->user);
        GiveWelcomePointJob::dispatch($event->user);
    }
}
