<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $event->title }}</h2>
    </x-slot>

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
                            @php
                                $statusClass = match($event->status) {
                                    'open' => 'bg-green-100 text-green-800',
                                    'ongoing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'closed' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $statusClass }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">About</h3>
                    <p class="text-gray-700">{{ $event->description }}</p>
                </div>

                @php
                    $capacity = $event->max_attendees ?? $event->max_participants;
                @endphp
                @if($capacity)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Seats Available</p>
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="seat-progress" class="bg-blue-600 h-2 rounded-full" style="width: {{ $capacity > 0 ? ($event->confirmed_count / $capacity) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <span id="seat-count" class="ml-4 font-semibold text-lg">
                                {{ $event->available_seats }} / {{ $capacity }}
                            </span>
                        </div>
                    </div>
                @endif

                @auth
                    @if((Auth::user()->hasRole('organizer') && $event->user_id === Auth::id()) || Auth::user()->hasRole('admin') || Auth::user()->role === 'admin')
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Event
                            </a>
                            <a href="{{ route('check-in.index', $event) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Check-in Attendees
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-medium" onclick="return confirm('Delete this event?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Event
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

                        @if($isRegistered)
                            <div class="p-4 bg-green-50 rounded-lg border-2 border-green-200 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-green-800 font-semibold">You are registered for this event</p>
                                </div>
                                <form action="{{ route('registrations.destroy', Auth::user()->registrations()->where('event_id', $event->id)->first()) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancel Registration
                                    </button>
                                </form>
                            </div>
                        @elseif(($event->available_seats === null || $event->available_seats > 0) && $event->status === 'open' && $event->start_datetime->isFuture())
                            <form action="{{ route('registrations.store', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-lg font-semibold shadow-lg hover:shadow-xl transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    Register Now
                                </button>
                            </form>
                        @elseif($event->start_datetime->isPast())
                            <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed text-lg font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Registration Closed
                            </button>
                        @else
                            <button disabled class="inline-flex items-center px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed text-lg font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Event Full
                            </button>
                        @endif
                    @endif
                @endauth
            </div>
        </div>
    </div>
    </div>

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
    </div>
</x-app-layout>
