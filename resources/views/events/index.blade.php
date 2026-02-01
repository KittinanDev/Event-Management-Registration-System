<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Events</h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">

                @auth
                    @if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-md hover:shadow-lg transition mb-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Event
                        </a>
                    @endif
                @endauth

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $event->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                                
                                <div class="space-y-2 text-sm text-gray-700 mb-4">
                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                    <p><strong>Date:</strong> {{ $event->start_datetime->format('M d, Y H:i') }}</p>
                                    @php
                                        $capacity = $event->max_attendees ?? $event->max_participants;
                                    @endphp
                                    @if($capacity)
                                        <p><strong>Capacity:</strong> {{ $event->confirmed_count }} / {{ $capacity }}</p>
                                    @endif
                                </div>

                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 font-medium transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No events available</p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
