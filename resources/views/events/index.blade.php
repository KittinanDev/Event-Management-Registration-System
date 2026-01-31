@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Events</h2>

                @auth
                    @if(auth()->user()->hasRole('organizer'))
                        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mb-6">
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
                                    @if($event->max_participants)
                                        <p><strong>Capacity:</strong> {{ $event->confirmed_count }} / {{ $event->max_participants }}</p>
                                    @endif
                                </div>

                                <a href="{{ route('events.show', $event) }}" class="inline-block px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
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
</div>
@endsection
