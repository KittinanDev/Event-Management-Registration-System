<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Registrations</h2>
    </x-slot>

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
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $registration->status)) }}</p>
                                @if($registration->check_in_time)
                                    <p><strong>Check-in:</strong> {{ $registration->check_in_time->format('M d, Y H:i') }}</p>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $registration->event) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded text-center hover:bg-blue-700">
                                    View Event
                                </a>

                                @if($registration->status === 'registered')
                                    @if(now()->between($registration->event->start_datetime, $registration->event->end_datetime))
                                        <form action="{{ route('registrations.check-in', $registration) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                Check-in
                                            </button>
                                        </form>
                                    @elseif(now()->lt($registration->event->start_datetime))
                                        <button type="button" class="flex-1 px-3 py-2 bg-gray-300 text-gray-700 text-sm rounded cursor-not-allowed">
                                            Check-in (Not started)
                                        </button>
                                    @else
                                        <button type="button" class="flex-1 px-3 py-2 bg-gray-300 text-gray-700 text-sm rounded cursor-not-allowed">
                                            Check-in Closed
                                        </button>
                                    @endif

                                    <form action="{{ route('registrations.destroy', $registration) }}" method="POST" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700" onclick="return confirm('Cancel registration?')">
                                            Cancel
                                        </button>
                                    </form>
                                @elseif($registration->status === 'checked_in')
                                    <span class="flex-1 px-3 py-2 bg-green-100 text-green-800 text-sm rounded text-center">
                                        Completed
                                    </span>
                                @elseif($registration->status === 'no_show')
                                    <span class="flex-1 px-3 py-2 bg-red-100 text-red-800 text-sm rounded text-center">
                                        No Show
                                    </span>
                                @else
                                    <span class="flex-1 px-3 py-2 bg-gray-100 text-gray-800 text-sm rounded text-center">
                                        {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                                    </span>
                                @endif
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
</x-app-layout>
