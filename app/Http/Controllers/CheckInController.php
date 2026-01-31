<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function index(Event $event)
    {
        $this->authorize('checkIn', $event);
        
        $registrations = $event->registrations()
            ->where('status', 'confirmed')
            ->with('user', 'checkIn')
            ->paginate(15);

        return view('events.check-in', ['event' => $event, 'registrations' => $registrations]);
    }

    public function store(Request $request, Event $event, Registration $registration)
    {
        $this->authorize('checkIn', $event);

        if ($registration->event_id !== $event->id) {
            return back()->with('error', 'Invalid registration');
        }

        CheckIn::firstOrCreate(
            ['registration_id' => $registration->id],
            ['checked_in_by' => Auth::id()]
        );

        return back()->with('success', 'Check-in successful');
    }

    public function statistics(Event $event)
    {
        $this->authorize('checkIn', $event);

        $totalRegistrations = $event->registrations()->where('status', 'confirmed')->count();
        $checkedIn = $event->registrations()
            ->whereHas('checkIn')
            ->count();

        $checkInRate = $totalRegistrations > 0 ? round(($checkedIn / $totalRegistrations) * 100, 2) : 0;

        return view('events.statistics', [
            'event' => $event,
            'totalRegistrations' => $totalRegistrations,
            'checkedIn' => $checkedIn,
            'checkInRate' => $checkInRate,
        ]);
    }
}
