<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GiveWelcomePointJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Log::info('QUEUE: Give 100 welcome points to: ' . $this->user->email);
    }
}
