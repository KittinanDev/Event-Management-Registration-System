<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Welcome back, {{ Auth::user()->first_name ?? Auth::user()->name }}!</p>
            <h2 class="text-xl font-bold text-white leading-tight">My Dashboard</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Available Events --}}
            <div>
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Available Events</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @forelse($availableEvents as $event)
                        <div class="hover-card card p-5">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-base font-semibold text-white leading-snug">{{ $event->title }}</h4>
                                <span class="chip-open ml-3 shrink-0">Open</span>
                            </div>
                            <div class="text-sm text-slate-400 space-y-1 mb-4">
                                <p class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->location }}
                                </p>
                                <p>{{ $event->start_datetime->format('M d, Y H:i') }} &ndash; {{ $event->end_datetime->format('H:i') }}</p>
                                @php $capacity = $event->max_attendees ?? $event->max_participants; @endphp
                                <p>Attendees: {{ $event->current_attendees }}{{ $capacity ? ' / '.$capacity : '' }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $event) }}" class="btn-slate text-sm py-1.5 px-3 inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                                <form action="{{ route('registrations.store', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-green text-sm py-1.5 px-3 inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Join Event
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="card p-8 text-center col-span-2">
                            <p class="text-slate-500">No available events right now.</p>
                            <a href="{{ route('events.index') }}" class="btn-green text-sm py-1.5 px-4 mt-3 inline-block">Browse All Events</a>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Activities --}}
            <div>
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">My Activities &ndash; Upcoming</h3>
                <div class="space-y-4">
                    @forelse($upcoming as $registration)
                        <div class="card p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="text-base font-semibold text-white mb-1">{{ $registration->event->title }}</h4>
                                    <p class="text-sm text-slate-400">{{ $registration->event->start_datetime->format('M d, Y H:i') }} &ndash; {{ $registration->event->end_datetime->format('H:i') }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Status: <span class="chip-{{ $registration->status }}">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span></p>
                                </div>
                                <a href="{{ route('events.show', $registration->event) }}" class="btn-slate text-xs py-1 px-2.5 inline-flex items-center gap-1 shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                            </div>
                            <div class="flex gap-2">
                                @if($registration->status === 'registered')
                                    @if(now() >= $registration->event->start_datetime && now() <= $registration->event->end_datetime)
                                        <form action="{{ route('registrations.check-in', $registration) }}" method="POST">
                                            @csrf
                                            <button class="btn-green text-sm py-1.5 px-3 inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                Check-in
                                            </button>
                                        </form>
                                    @elseif(now()->lt($registration->event->start_datetime))
                                        <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm bg-white/5 text-slate-500 cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Not Yet
                                        </button>
                                    @else
                                        <button type="button" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm bg-white/5 text-slate-500 cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Expired
                                        </button>
                                    @endif
                                @elseif($registration->status === 'checked_in')
                                    <span class="chip-checked_in">âœ“ Completed</span>
                                @else
                                    <span class="chip-{{ $registration->status }}">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="card p-8 text-center">
                            <p class="text-slate-500">No upcoming activities.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- History --}}
            <div>
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">My Activities &ndash; History</h3>
                <div class="space-y-3">
                    @forelse($history as $registration)
                        <div class="card p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-white">{{ $registration->event->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $registration->event->start_datetime->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($registration->status === 'checked_in')
                                        <span class="chip-checked_in">Checked In</span>
                                    @elseif($registration->status === 'no_show')
                                        <span class="chip-no_show">No Show</span>
                                    @else
                                        <span class="chip-cancelled">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                    @endif
                                    <a href="{{ route('events.show', $registration->event) }}" class="btn-slate text-xs py-1 px-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-8 text-center">
                            <p class="text-slate-500">No activity history.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
