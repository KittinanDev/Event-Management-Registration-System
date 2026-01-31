@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-gray-600 text-sm">Location</p>
                        <p class="text-lg font-semibold">{{ $event->location }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Start Date</p>
                        <p class="text-lg font-semibold">{{ $event->start_datetime->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="text-lg font-semibold">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">About</h3>
                    <p class="text-gray-700">{{ $event->description }}</p>
                </div>

                @if($event->max_participants)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Seats Available</p>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="seat-progress" class="bg-blue-600 h-2 rounded-full" style="width: {{ ($event->confirmed_count / $event->max_participants) * 100 }}%"></div>
                                </div>
                            </div>
                            <span id="seat-count" class="ml-4 font-semibold text-lg">
                                {{ $event->available_seats }} / {{ $event->max_participants }}
                            </span>
                        </div>
                    </div>
                @endif

                @auth
                    @if(Auth::user()->hasRole('organizer') && $event->user_id === Auth::id())
                        <div class="space-y-2">
                            <a href="{{ route('events.edit', $event) }}" class="inline-block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                                Edit Event
                            </a>
                            <a href="{{ route('check-in.index', $event) }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                Check-in Attendees
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('Delete this event?')">
                                    Delete Event
                                </button>
                            </form>
                        </div>
                    @else
                        @php
                            $isRegistered = Auth::check() && Auth::user()->registrations()
                                ->where('event_id', $event->id)
                                ->where('status', 'confirmed')
                                ->exists();
                        @endphp

                        @if($isRegistered)
                            <div class="p-4 bg-green-50 rounded-lg flex items-center justify-between">
                                <p class="text-green-800">✓ You are registered for this event</p>
                                <form action="{{ route('registrations.destroy', Auth::user()->registrations()->where('event_id', $event->id)->first()) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                        Cancel Registration
                                    </button>
                                </form>
                            </div>
                        @elseif($event->available_seats === null || $event->available_seats > 0)
                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-lg font-semibold">
                                    Register Now
                                </button>
                            </form>
                        @else
                            <button disabled class="px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed text-lg font-semibold">
                                Event Full
                            </button>
                        @endif
                    @endauth
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const eventId = {{ $event->id }};
    
    // Poll for seat updates every 5 seconds
    setInterval(() => {
        fetch(`/api/events/${eventId}/seats`)
            .then(response => response.json())
            .then(data => {
                const seatCount = document.getElementById('seat-count');
                const seatProgress = document.getElementById('seat-progress');
                
                if (seatCount) {
                    seatCount.textContent = `${data.available} / ${data.capacity}`;
                }
                
                if (seatProgress && data.capacity > 0) {
                    const percentage = ((data.capacity - data.available) / data.capacity) * 100;
                    seatProgress.style.width = percentage + '%';
                }
            })
            .catch(error => console.error('Error fetching seat data:', error));
    }, 5000);
</script>
@endpush
@endsection
