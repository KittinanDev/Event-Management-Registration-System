<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private const STATUS_LABELS = ['open', 'ongoing', 'closed', 'completed', 'cancelled'];

    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($this->isAdmin($user)) {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    public function userDashboard()
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless($user instanceof User, 403);

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
        $this->ensureAdmin();

        // Get all events grouped by status
        $allEvents = Event::orderBy('start_datetime', 'desc')->get();
        
        $openEvents = $allEvents->where('status', 'open');
        $ongoingEvents = $allEvents->where('status', 'ongoing');
        $completedEvents = $allEvents->where('status', 'completed');
        
        // Statistics
        $totalEvents = $allEvents->count();
        $totalRegistrations = Registration::count();
        $totalCheckedIn = Registration::where('status', 'checked_in')->count();
        $totalNoShow = Registration::where('status', 'no_show')->count();
        
        // Upcoming events (next 7 days)
        $upcomingEvents = Event::where('start_datetime', '>', now())
            ->where('start_datetime', '<=', now()->addDays(7))
            ->orderBy('start_datetime')
            ->get();

        $statusCounts = Event::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $eventsByStatusData = collect(self::STATUS_LABELS)->map(fn (string $status) => (int) ($statusCounts[$status] ?? 0));

        $monthExpression = match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', registered_at)",
            'pgsql' => "to_char(registered_at, 'YYYY-MM')",
            default => "DATE_FORMAT(registered_at, '%Y-%m')",
        };

        $monthlyRows = Registration::query()
            ->selectRaw("{$monthExpression} as month_key")
            ->selectRaw('COUNT(*) as total')
            ->where('registered_at', '>=', now()->copy()->subMonths(2)->startOfMonth())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key');

        $monthKeys = collect(range(2, 0, -1))
            ->map(fn (int $offset) => now()->copy()->subMonths($offset)->format('Y-m'))
            ->values();

        $monthlyRegistrationLabels = $monthKeys
            ->map(fn (string $monthKey) => Carbon::createFromFormat('Y-m', $monthKey)->format('M Y'))
            ->values();

        $monthlyRegistrationsData = $monthKeys
            ->map(fn (string $monthKey) => (int) optional($monthlyRows->get($monthKey))->total)
            ->values();

        $registrationStatusData = [
            (int) Registration::where('status', 'registered')->count(),
            (int) Registration::where('status', 'checked_in')->count(),
            (int) Registration::where('status', 'cancelled')->count(),
            (int) Registration::where('status', 'no_show')->count(),
        ];

        $chartData = [
            'eventsByStatus' => [
                'labels' => collect(self::STATUS_LABELS)->map(fn (string $status) => ucfirst($status))->values(),
                'values' => $eventsByStatusData,
            ],
            'monthlyRegistrations' => [
                'labels' => $monthlyRegistrationLabels,
                'values' => $monthlyRegistrationsData,
            ],
            'registrationStatus' => [
                'labels' => ['Registered', 'Checked In', 'Cancelled', 'No Show'],
                'values' => $registrationStatusData,
            ],
        ];

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
            'chartData' => $chartData,
        ]);
    }

    public function calendar()
    {
        $this->ensureAdmin();

        $events = Event::query()
            ->orderBy('start_datetime')
            ->get()
            ->map(function (Event $event) {
                $statusColor = match ($event->status) {
                    'open' => '#16a34a',
                    'ongoing' => '#2563eb',
                    'closed' => '#f59e0b',
                    'completed' => '#6b7280',
                    'cancelled' => '#dc2626',
                    default => '#4b5563',
                };

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_datetime?->toIso8601String(),
                    'end' => $event->end_datetime?->toIso8601String(),
                    'url' => route('events.show', $event),
                    'backgroundColor' => $statusColor,
                    'borderColor' => $statusColor,
                    'extendedProps' => [
                        'location' => $event->location,
                        'status' => $event->status,
                    ],
                ];
            })
            ->values();

        return view('dashboard.calendar', [
            'calendarEvents' => $events,
        ]);
    }

    public function exportSummaryPdf()
    {
        $this->ensureAdmin();

        $eventCount = Event::count();
        $registrationCount = Registration::count();
        $checkedInCount = Registration::where('status', 'checked_in')->count();
        $cancelledCount = Registration::where('status', 'cancelled')->count();
        $noShowCount = Registration::where('status', 'no_show')->count();

        $statusSummary = Event::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $pdf = Pdf::loadView('reports.admin-summary-pdf', [
            'generatedAt' => now(),
            'eventCount' => $eventCount,
            'registrationCount' => $registrationCount,
            'checkedInCount' => $checkedInCount,
            'cancelledCount' => $cancelledCount,
            'noShowCount' => $noShowCount,
            'statusSummary' => $statusSummary,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('admin-summary-' . now()->format('Ymd-His') . '.pdf');
    }

    private function ensureAdmin(): void
    {
        abort_unless($this->isAdmin(Auth::user()), 403);
    }

    private function isAdmin(?User $user): bool
    {
        return (bool) ($user && ($user->hasRole('admin') || $user->role === 'admin'));
    }
}
