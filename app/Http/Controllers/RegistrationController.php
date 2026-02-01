<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->availableSeats !== null && $event->availableSeats <= 0) {
            return back()->with('error', 'Event is full');
        }

        $exists = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Already registered for this event');
        }

        DB::transaction(function () use ($event) {
            Event::findOrFail($event->id)->lockForUpdate();
            
            Registration::create([
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'status' => 'confirmed',
            ]);
        });

        return back()->with('success', 'Successfully registered');
    }

    public function destroy(Registration $registration)
    {
        if (Auth::id() !== $registration->user_id && !Auth::user()->hasRole('organizer')) {
            return back()->with('error', 'Unauthorized');
        }

        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Registration cancelled');
    }

    public function myRegistrations()
    {
        $registrations = Auth::user()->registrations()
            ->where('status', 'confirmed')
            ->with('event')
            ->paginate(10);

        return view('registrations.my-registrations', ['registrations' => $registrations]);
    }
}
