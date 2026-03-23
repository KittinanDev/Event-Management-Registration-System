<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class FinalizeEventsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $events = Event::where('end_datetime', '<', now())
            ->whereIn('status', ['open', 'closed', 'ongoing'])
            ->get();

        foreach ($events as $event) {
            DB::transaction(function () use ($event) {
                Registration::where('event_id', $event->id)
                    ->where('status', 'registered')
                    ->update(['status' => 'no_show']);

                $event->update(['status' => 'completed']);
            });
        }
    }
}
