<?php

namespace App\Jobs;

use App\Mail\EventReminderMail;
use App\Models\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEventRemindersJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Send reminders for events starting tomorrow
        $tomorrowStart = now()->addDay()->startOfDay();
        $tomorrowEnd = now()->addDay()->endOfDay();

        Event::whereBetween('start_datetime', [$tomorrowStart, $tomorrowEnd])
            ->published()
            ->get()
            ->each(function ($event) {
                Mail::send(new EventReminderMail($event));
            });
    }
}
