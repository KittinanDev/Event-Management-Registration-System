<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Live Check-in</p>
                <h2 class="text-xl font-bold text-white leading-tight">{{ $event->title }}</h2>
            </div>
            <a href="{{ route('events.show', $event) }}" class="btn-slate inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Event
            </a>
        </div>
    </x-slot>

<div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Live Stats Bar --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="card p-5 text-center">
                    <p class="label text-xs uppercase tracking-wider mb-1">Total Registered</p>
                    <p class="text-3xl font-bold text-white">{{ $registrations->total() }}</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="label text-xs uppercase tracking-wider mb-1">Checked In</p>
                    <p class="text-3xl font-bold text-green-400" id="checkin-count">0</p>
                </div>
                <div class="card p-5 text-center">
                    <p class="label text-xs uppercase tracking-wider mb-1">Check-in Rate</p>
                    <p class="text-3xl font-bold text-amber-400" id="checkin-rate">0%</p>
                </div>
            </div>

            {{-- Attendees Table --}}
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="dark-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                                <tr>
                                    <td class="font-medium text-white">{{ $registration->user->full_name ?? $registration->user->name }}</td>
                                    <td class="text-slate-300">{{ $registration->user->phone ?? '-' }}</td>
                                    <td>
                                        <span class="chip-{{ $registration->status }}">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                    </td>
                                    <td>
                                        @php $withinTime = now()->between($event->start_datetime, $event->end_datetime); @endphp
                                        @if($registration->status !== 'checked_in')
                                            @if($withinTime)
                                                <form action="{{ route('check-in.store', [$event, $registration]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn-green text-sm py-1 px-3">
                                                        Check In
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="px-3 py-1 rounded text-sm bg-white/5 text-slate-500 cursor-not-allowed">Check In</button>
                                            @endif
                                        @else
                                            <span class="text-green-400 text-sm">{{ $registration->check_in_time?->format('M d, H:i') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-slate-500 py-10">No registrations yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
</div>

<script nonce="">
    const eventId = {{ $event->id }};
    
    // Update check-in statistics every 3 seconds
    setInterval(() => {
        fetch(`/api/events/${eventId}/check-in-stats`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('checkin-count').textContent = data.checked_in;
                document.getElementById('checkin-rate').textContent = data.rate + '%';
            })
            .catch(error => console.error('Error fetching stats:', error));
    }, 3000);
</script>
    </div>
</x-app-layout>
