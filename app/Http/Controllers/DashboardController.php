<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    public function userDashboard()
    {
        $user = Auth::user();

        $availableEvents = Event::query()
            ->where('status', 'open')
            ->where('start_datetime', '>', now())
            ->where(function ($query) {
                $query->whereNull('max_attendees')
                    ->orWhereColumn('current_attendees', '<', 'max_attendees');
            })
            ->whereDoesntHave('registrations', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('start_datetime')
            ->get();

        $registrations = $user->registrations()
            ->with('event')
            ->get()
            ->filter(function ($registration) {
                return $registration->event !== null;
            })
            ->sortByDesc(function ($registration) {
                return $registration->event->start_datetime;
            });

        $upcoming = $registrations->filter(function ($registration) {
            return $registration->event && $registration->event->end_datetime >= now();
        });

        $history = $registrations->filter(function ($registration) {
            return $registration->event && $registration->event->end_datetime < now();
        });

        return view('dashboard.user', [
            'availableEvents' => $availableEvents,
            'upcoming' => $upcoming,
            'history' => $history,
        ]);
    }

    public function adminDashboard()
    {
        // Get all events grouped by status
        $allEvents = Event::orderBy('start_datetime', 'desc')->get();
        
        $openEvents = $allEvents->where('status', 'open');
        $ongoingEvents = $allEvents->where('status', 'ongoing');
        $completedEvents = $allEvents->where('status', 'completed');
        
        // Statistics
        $totalEvents = $allEvents->count();
        $totalRegistrations = \App\Models\Registration::count();
        $totalCheckedIn = \App\Models\Registration::where('status', 'checked_in')->count();
        $totalNoShow = \App\Models\Registration::where('status', 'no_show')->count();
        
        // Upcoming events (next 7 days)
        $upcomingEvents = Event::where('start_datetime', '>', now())
            ->where('start_datetime', '<=', now()->addDays(7))
            ->orderBy('start_datetime')
            ->get();

        return view('dashboard.admin', [
            'allEvents' => $allEvents,
            'openEvents' => $openEvents,
            'ongoingEvents' => $ongoingEvents,
            'completedEvents' => $completedEvents,
            'totalEvents' => $totalEvents,
            'totalRegistrations' => $totalRegistrations,
            'totalCheckedIn' => $totalCheckedIn,
            'totalNoShow' => $totalNoShow,
            'upcomingEvents' => $upcomingEvents,
        ]);
    }
}
