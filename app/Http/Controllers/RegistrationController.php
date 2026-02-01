<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RegistrationController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Event $event)
    {
        if ($event->status !== 'open') {
            return back()->with('error', 'Event is not open for registration');
        }

        if ($event->start_datetime <= now()) {
            return back()->with('error', 'Event registration is closed');
        }

        // Check for existing active registration
        $existingRegistration = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['registered', 'checked_in'])
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Already registered for this event');
        }

        // Check for cancelled registration and restore it
        $cancelledRegistration = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', 'cancelled')
            ->first();

        $registration = DB::transaction(function () use ($event, $cancelledRegistration) {
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->firstOrFail();

            $max = $lockedEvent->max_attendees ?? $lockedEvent->max_participants;
            if ($max !== null && $lockedEvent->current_attendees >= $max) {
                return null;
            }

            if ($cancelledRegistration) {
                // Restore cancelled registration
                $cancelledRegistration->update([
                    'status' => 'registered',
                    'registered_at' => now(),
                ]);
                $registration = $cancelledRegistration;
            } else {
                // Create new registration
                $registration = Registration::create([
                    'event_id' => $lockedEvent->id,
                    'user_id' => Auth::id(),
                    'status' => 'registered',
                    'registered_at' => now(),
                ]);
            }

            $lockedEvent->increment('current_attendees');

            if ($max !== null && $lockedEvent->current_attendees >= $max) {
                $lockedEvent->update(['status' => 'closed']);
            }

            return $registration;
        });

        if (!$registration) {
            return back()->with('error', 'Event is full');
        }

        return back()->with('success', 'Successfully registered');
    }

    public function destroy(Registration $registration)
    {
        if (Auth::id() !== $registration->user_id && !Auth::user()->hasRole('organizer') && !Auth::user()->hasRole('admin') && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized');
        }

        DB::transaction(function () use ($registration) {
            $event = Event::where('id', $registration->event_id)->lockForUpdate()->first();
            $registration->update(['status' => 'cancelled']);

            if ($event && $event->current_attendees > 0) {
                $event->decrement('current_attendees');

                if ($event->status === 'closed') {
                    $event->update(['status' => 'open']);
                }
            }
        });

        return back()->with('success', 'Registration cancelled');
    }

    public function myRegistrations()
    {
        $registrations = Auth::user()->registrations()
            ->whereIn('status', ['registered', 'checked_in', 'cancelled', 'no_show'])
            ->with('event')
            ->paginate(10);

        return view('registrations.my-registrations', ['registrations' => $registrations]);
    }

    public function checkIn(Registration $registration)
    {
        if (Auth::id() !== $registration->user_id) {
            return back()->with('error', 'Unauthorized');
        }

        $event = $registration->event;

        if ($registration->status !== 'registered') {
            return back()->with('error', 'Invalid status for check-in');
        }

        if (now()->lt($event->start_datetime) || now()->gt($event->end_datetime)) {
            return back()->with('error', 'Check-in is not available at this time');
        }

        $registration->update([
            'status' => 'checked_in',
            'check_in_time' => now(),
        ]);

        return back()->with('success', 'Checked in successfully');
    }
}
