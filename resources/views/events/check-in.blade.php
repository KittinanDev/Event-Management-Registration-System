<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Check-in: {{ $event->title }}</h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $event->title }} - Check-in Attendees</h2>

                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Total Registered</p>
                            <p class="text-2xl font-bold">{{ $registrations->total() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Checked In</p>
                            <p class="text-2xl font-bold" id="checkin-count">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Check-in Rate</p>
                            <p class="text-2xl font-bold" id="checkin-rate">0%</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2 text-left">Name</th>
                                <th class="border border-gray-300 p-2 text-left">Phone</th>
                                <th class="border border-gray-300 p-2 text-left">Status</th>
                                <th class="border border-gray-300 p-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 p-2">{{ $registration->user->full_name ?? $registration->user->name }}</td>
                                    <td class="border border-gray-300 p-2">{{ $registration->user->phone ?? '-' }}</td>
                                    <td class="border border-gray-300 p-2">
                                        @if($registration->status === 'checked_in')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-bold">✓ Checked In</span>
                                        @elseif($registration->status === 'registered')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded font-bold">Registered</span>
                                        @elseif($registration->status === 'no_show')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded font-bold">No Show</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded font-bold">{{ ucfirst($registration->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        @php
                                            $withinTime = now()->between($event->start_datetime, $event->end_datetime);
                                        @endphp
                                        @if($registration->status !== 'checked_in')
                                            @if($withinTime)
                                                <form action="{{ route('check-in.store', [$event, $registration]) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                        Check In
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded cursor-not-allowed">Check In</button>
                                            @endif
                                        @else
                                            <span class="text-green-600">{{ $registration->check_in_time?->format('M d, H:i') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="border border-gray-300 p-2 text-center text-gray-500">
                                        No registrations yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $registrations->links() }}
                </div>

                <div class="mt-6">
                    <a href="{{ route('events.show', $event) }}" class="text-blue-600 hover:text-blue-900">Back to Event</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
