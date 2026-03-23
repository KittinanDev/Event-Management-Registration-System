<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-display text-xl font-bold text-white">{{ $event->title }}</h2>
            <a href="{{ route('events.index') }}" class="btn-slate text-xs">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

        <div class="card p-6 sm:p-8">
            {{-- Title + Status --}}
            <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="font-display text-2xl font-extrabold text-white sm:text-3xl">{{ $event->title }}</h1>
                    <p class="mt-1 text-sm text-slate-400">
                        {{ $event->start_datetime->format('d M Y, H:i') }} — {{ $event->end_datetime->format('H:i') }}
                    </p>
                </div>
                @php
                    $chipClass = match($event->status) {
                        'open' => 'chip-open', 'ongoing' => 'chip-ongoing',
                        'closed' => 'chip-closed', 'completed' => 'chip-completed',
                        default => 'chip-draft',
                    };
                @endphp
                <span class="chip {{ $chipClass }}"><span class="h-1 w-1 rounded-full bg-current"></span>{{ $event->status }}</span>
            </div>

            {{-- Info Grid --}}
            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-white/4 p-4">
                    <p class="text-xs text-slate-500 mb-1">Location</p>
                    <p class="text-sm font-semibold text-white">{{ $event->location }}</p>
                </div>
                <div class="rounded-xl bg-white/4 p-4">
                    <p class="text-xs text-slate-500 mb-1">Start</p>
                    <p class="text-sm font-semibold text-white">{{ $event->start_datetime->format('d M Y H:i') }}</p>
                </div>
                <div class="rounded-xl bg-white/4 p-4">
                    <p class="text-xs text-slate-500 mb-1">End</p>
                    <p class="text-sm font-semibold text-white">{{ $event->end_datetime->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <h3 class="mb-2 text-sm font-semibold uppercase tracking-widest text-slate-400">About</h3>
                <p class="text-sm leading-7 text-slate-300">{{ $event->description }}</p>
            </div>

            {{-- Capacity --}}
            @php $capacity = $event->max_attendees ?? $event->max_participants; @endphp
            @if($capacity)
                <div class="mb-6 rounded-xl bg-white/4 p-4">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="text-slate-400">Seats</span>
                        <span id="seat-count" class="font-semibold text-white">{{ $event->available_seats }} / {{ $capacity }} available</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-white/10">
                        <div id="seat-progress" class="h-2 rounded-full bg-green-500 transition-all duration-500"
                            style="width: {{ $capacity > 0 ? min(($event->confirmed_count / $capacity) * 100, 100) : 0 }}%"></div>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            @auth
                @if((Auth::user()->hasRole('organizer') && $event->user_id === Auth::id()) || Auth::user()->hasRole('admin') || Auth::user()->role === 'admin')
                    <div class="flex flex-wrap gap-2 border-t border-white/8 pt-5">
                        <a href="{{ route('events.edit', $event) }}" class="btn-amber">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Event
                        </a>
                        <a href="{{ route('check-in.index', $event) }}" class="btn-purple">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Check-in Attendees
                        </a>
                        <a href="{{ route('check-in.statistics', $event) }}" class="btn-slate">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Statistics
                        </a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-red" onclick="return confirm('Delete this event?')">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                @else
                    @php
                        $isRegistered = Auth::check() && Auth::user()->registrations()
                            ->where('event_id', $event->id)
                            ->whereIn('status', ['registered', 'checked_in'])
                            ->exists();
                    @endphp
                    <div class="border-t border-white/8 pt-5">
                        @if($isRegistered)
                            <div class="mb-4 flex items-center justify-between rounded-xl bg-green-500/10 border border-green-500/20 px-4 py-3">
                                <div class="flex items-center gap-2.5 text-sm font-semibold text-green-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    You are registered for this event
                                </div>
                                <form action="{{ route('registrations.destroy', Auth::user()->registrations()->where('event_id', $event->id)->first()) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-red text-xs px-3 py-1.5">Cancel Registration</button>
                                </form>
                            </div>
                        @elseif(($event->available_seats === null || $event->available_seats > 0) && $event->status === 'open' && $event->start_datetime->isFuture())
                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-green px-6 py-3 text-base">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    Register Now
                                </button>
                            </form>
                        @else
                            <button disabled class="btn-slate cursor-not-allowed opacity-60">
                                {{ $event->start_datetime->isPast() ? 'Registration Closed' : 'Event Full' }}
                            </button>
                        @endif
                    </div>
                @endif
            @else
                <div class="border-t border-white/8 pt-5">
                    <a href="{{ route('login') }}" class="btn-green">Login to Register</a>
                </div>
            @endauth
        </div>
    </div>

<script>
    const eventId = {{ $event->id }};
    setInterval(() => {
        fetch(`/api/events/${eventId}/seats`)
            .then(r => r.json())
            .then(data => {
                const sc = document.getElementById('seat-count');
                const sp = document.getElementById('seat-progress');
                if (sc) sc.textContent = `${data.available} / ${data.capacity} available`;
                if (sp && data.capacity > 0) sp.style.width = ((data.capacity - data.available) / data.capacity * 100) + '%';
            })
            .catch(() => {});
            .catch(error => console.error('Error fetching seat data:', error));
    }, 5000);
</script>
    </div>
</x-app-layout>
