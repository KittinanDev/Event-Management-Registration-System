@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">My Registrations</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($registrations as $registration)
                        <div class="border rounded-lg p-4 hover:shadow-lg transition">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $registration->event->title }}</h3>
                            
                            <div class="space-y-2 text-sm text-gray-700 mb-4">
                                <p><strong>Date:</strong> {{ $registration->event->start_datetime->format('M d, Y H:i') }}</p>
                                <p><strong>Location:</strong> {{ $registration->event->location }}</p>
                                <p><strong>Registered:</strong> {{ $registration->registered_at->format('M d, Y') }}</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $registration->event) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded text-center hover:bg-blue-700">
                                    View Event
                                </a>
                                <form action="{{ route('registrations.destroy', $registration) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700" onclick="return confirm('Cancel registration?')">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center col-span-2">You haven't registered for any events yet</p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
