<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Available Events</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($availableEvents as $event)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $event->title }}</h4>
                                <div class="text-sm text-gray-700 space-y-1 mb-4">
                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                    <p><strong>Date:</strong> {{ $event->start_datetime->format('M d, Y H:i') }} - {{ $event->end_datetime->format('H:i') }}</p>
                                    @php
                                        $capacity = $event->max_attendees ?? $event->max_participants;
                                    @endphp
                                    @if($capacity)
                                        <p><strong>Attendees:</strong> {{ $event->current_attendees }} / {{ $capacity }}</p>
                                    @else
                                        <p><strong>Attendees:</strong> {{ $event->current_attendees }}</p>
                                    @endif
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <form action="{{ route('registrations.store', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            Join Event
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No available events right now.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">My Activities – Upcoming</h3>

                    <div class="space-y-4">
                        @forelse($upcoming as $registration)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h4 class="text-lg font-semibold">{{ $registration->event->title }}</h4>
                                        <p class="text-sm text-gray-700">{{ $registration->event->start_datetime->format('M d, Y H:i') }} - {{ $registration->event->end_datetime->format('H:i') }}</p>
                                        <p class="text-sm text-gray-600">Status: {{ ucfirst(str_replace('_', ' ', $registration->status)) }}</p>
                                    </div>
                                    <a href="{{ route('events.show', $registration->event) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </div>

                                <div class="flex gap-2">
                                    @if($registration->status === 'registered')
                                        @if(now() >= $registration->event->start_datetime && now() <= $registration->event->end_datetime)
                                            <form action="{{ route('registrations.check-in', $registration) }}" method="POST">
                                                @csrf
                                                <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                    </svg>
                                                    Check-in
                                                </button>
                                            </form>
                                        @elseif(now()->lt($registration->event->start_datetime))
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Not Yet
                                            </button>
                                        @else
                                            <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Expired
                                            </button>
                                        @endif
                                    @elseif($registration->status === 'checked_in')
                                        <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No upcoming activities.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">My Activities – History</h3>

                    <div class="space-y-4">
                        @forelse($history as $registration)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h4 class="text-lg font-semibold">{{ $registration->event->title }}</h4>
                                        <p class="text-sm text-gray-700">{{ $registration->event->start_datetime->format('M d, Y H:i') }}</p>
                                    </div>
                                    <a href="{{ route('events.show', $registration->event) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                </div>
                                <p class="text-sm">
                                    @if($registration->status === 'checked_in')
                                        <span class="inline-flex items-center text-green-700 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Checked In
                                        </span>
                                    @elseif($registration->status === 'no_show')
                                        <span class="inline-flex items-center text-red-700 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            No Show
                                        </span>
                                    @else
                                        <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $registration->status)) }}</span>
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500">No activity history.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
