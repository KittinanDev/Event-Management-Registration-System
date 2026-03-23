<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Account</p>
            <h2 class="text-xl font-bold text-white leading-tight">My Registrations</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($registrations as $registration)
                    <div class="hover-card card overflow-hidden">
                        {{-- Status stripe --}}
                        @php
                            $stripe = match($registration->status) {
                                'checked_in' => 'bg-green-500',
                                'registered' => 'bg-blue-500',
                                'no_show'    => 'bg-red-500',
                                default      => 'bg-slate-600',
                            };
                        @endphp
                        <div class="h-1 {{ $stripe }}"></div>

                        <div class="p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-base font-semibold text-white leading-snug">{{ $registration->event->title }}</h3>
                                <span class="chip-{{ $registration->status }} ml-2 shrink-0">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                            </div>

                            <div class="space-y-1 text-sm text-slate-400 mb-4">
                                <p>{{ $registration->event->start_datetime->format('M d, Y H:i') }}</p>
                                <p class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $registration->event->location }}
                                </p>
                                <p>Registered: {{ $registration->registered_at->format('M d, Y') }}</p>
                                @if($registration->check_in_time)
                                    <p>Check-in: {{ $registration->check_in_time->format('M d, Y H:i') }}</p>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $registration->event) }}" class="btn-slate text-sm py-1.5 px-3 flex-1 text-center">View Event</a>

                                @if($registration->status === 'registered')
                                    @if(now()->between($registration->event->start_datetime, $registration->event->end_datetime))
                                        <form action="{{ route('registrations.check-in', $registration) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="btn-green text-sm py-1.5 px-3 w-full">Check-in</button>
                                        </form>
                                    @elseif(now()->lt($registration->event->start_datetime))
                                        <button type="button" class="flex-1 px-3 py-1.5 rounded-lg text-sm bg-white/5 text-slate-500 cursor-not-allowed">Not started</button>
                                    @else
                                        <button type="button" class="flex-1 px-3 py-1.5 rounded-lg text-sm bg-white/5 text-slate-500 cursor-not-allowed">Check-in Closed</button>
                                    @endif

                                    <form action="{{ route('registrations.destroy', $registration) }}" method="POST" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-red text-sm py-1.5 px-3 w-full" onclick="return confirm('Cancel registration?')">Cancel</button>
                                    </form>
                                @elseif($registration->status === 'checked_in')
                                    <span class="flex-1 px-3 py-1.5 rounded-lg text-sm text-center bg-green-500/10 text-green-400 border border-green-500/20">Completed</span>
                                @elseif($registration->status === 'no_show')
                                    <span class="flex-1 px-3 py-1.5 rounded-lg text-sm text-center bg-red-500/10 text-red-400 border border-red-500/20">No Show</span>
                                @else
                                    <span class="flex-1 px-3 py-1.5 rounded-lg text-sm text-center bg-white/5 text-slate-400">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card p-10 text-center col-span-2">
                        <svg class="w-12 h-12 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-slate-500 mb-3">You haven't registered for any events yet</p>
                        <a href="{{ route('events.index') }}" class="btn-green text-sm py-1.5 px-4 inline-block">Browse Events</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
