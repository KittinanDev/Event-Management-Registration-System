<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-xl font-bold text-white">Events</h2>
            @auth
                @if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                    <a href="{{ route('events.create') }}" class="btn-green">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Event
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($events as $event)
                @php
                    $chipClass = match($event->status) {
                        'open' => 'chip-open', 'ongoing' => 'chip-ongoing',
                        'closed' => 'chip-closed', 'completed' => 'chip-completed',
                        default => 'chip-draft',
                    };
                    $capacity = $event->max_attendees ?? $event->max_participants;
                @endphp
                <div class="card hover-card flex flex-col overflow-hidden">
                    <div class="h-1 w-full {{ $event->status === 'open' ? 'bg-green-500' : ($event->status === 'ongoing' ? 'bg-amber-400' : 'bg-slate-700') }}"></div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="mb-3 flex items-center justify-between">
                            <span class="chip {{ $chipClass }}"><span class="h-1 w-1 rounded-full bg-current"></span>{{ $event->status }}</span>
                            <span class="text-xs text-slate-500">{{ $event->start_datetime->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-base font-bold text-white line-clamp-2">{{ $event->title }}</h3>
                        <p class="mt-2 line-clamp-2 flex-1 text-sm leading-6 text-slate-400">{{ $event->description }}</p>
                        <div class="mt-4 space-y-1.5 border-t border-white/8 pt-4 text-xs text-slate-500">
                            <p class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ Str::limit($event->location, 32) }}
                            </p>
                            @if($capacity)
                                <p class="flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->confirmed_count }} / {{ $capacity }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="mt-4 inline-flex items-center text-xs font-semibold text-green-400 hover:text-green-300 transition-colors">
                            View Details
                            <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full card p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="mt-3 text-sm text-slate-500">No events available</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $events->links() }}</div>
    </div>
</x-app-layout>
