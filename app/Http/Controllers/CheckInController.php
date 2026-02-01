<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CheckInController extends Controller
{
    use AuthorizesRequests;
    public function index(Event $event)
    {
        $this->authorize('checkIn', $event);
        
        $registrations = $event->registrations()
            ->whereIn('status', ['registered', 'checked_in'])
            ->with('user')
            ->paginate(15);

        return view('events.check-in', ['event' => $event, 'registrations' => $registrations]);
    }

    public function store(Request $request, Event $event, Registration $registration)
    {
        $this->authorize('checkIn', $event);

        if ($registration->event_id !== $event->id) {
            return back()->with('error', 'Invalid registration');
        }

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

        return back()->with('success', 'Check-in successful');
    }

    public function statistics(Event $event)
    {
        $this->authorize('checkIn', $event);

        $totalRegistrations = $event->registrations()->whereIn('status', ['registered', 'checked_in'])->count();
        $checkedIn = $event->registrations()->where('status', 'checked_in')->count();

        $checkInRate = $totalRegistrations > 0 ? round(($checkedIn / $totalRegistrations) * 100, 2) : 0;

        return view('events.statistics', [
            'event' => $event,
            'totalRegistrations' => $totalRegistrations,
            'checkedIn' => $checkedIn,
            'checkInRate' => $checkInRate,
        ]);
    }

    public function stats(Event $event)
    {
        $totalRegistrations = $event->registrations()->whereIn('status', ['registered', 'checked_in'])->count();
        $checkedIn = $event->registrations()->where('status', 'checked_in')->count();

        $checkInRate = $totalRegistrations > 0 ? round(($checkedIn / $totalRegistrations) * 100, 2) : 0;

        return response()->json([
            'checked_in' => $checkedIn,
            'total' => $totalRegistrations,
            'rate' => $checkInRate,
        ]);
    }
}
