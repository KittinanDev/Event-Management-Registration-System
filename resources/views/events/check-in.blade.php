@extends('layouts.app')

@section('content')
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
                                <th class="border border-gray-300 p-2 text-left">Email</th>
                                <th class="border border-gray-300 p-2 text-left">Status</th>
                                <th class="border border-gray-300 p-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 p-2">{{ $registration->user->name }}</td>
                                    <td class="border border-gray-300 p-2">{{ $registration->user->email }}</td>
                                    <td class="border border-gray-300 p-2">
                                        @if($registration->checkIn)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded font-bold">✓ Checked In</span>
                                        @else
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded font-bold">Pending</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2">
                                        @if(!$registration->checkIn)
                                            <form action="{{ route('check-in.store', [$event, $registration]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                                    Check In
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-green-600">{{ $registration->checkIn->checked_in_at->format('M d, H:i') }}</span>
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

@push('scripts')
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
@endpush
@endsection
