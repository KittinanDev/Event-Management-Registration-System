<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-md hover:shadow-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Events</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                        </div>
                        <div class="text-4xl text-blue-500">📅</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Registrations</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalRegistrations }}</p>
                        </div>
                        <div class="text-4xl text-purple-500">👥</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Checked In</p>
                            <p class="text-3xl font-bold text-green-600">{{ $totalCheckedIn }}</p>
                        </div>
                        <div class="text-4xl text-green-500">✅</div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">No Show</p>
                            <p class="text-3xl font-bold text-red-600">{{ $totalNoShow }}</p>
                        </div>
                        <div class="text-4xl text-red-500">❌</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events (Next 7 Days) -->
            @if($upcomingEvents->count() > 0)
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">⏰ Upcoming Events (Next 7 Days)</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($upcomingEvents as $event)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h4>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold">
                                    {{ $event->start_datetime->diffForHumans(now(), \Carbon\Carbon::DIFF_RELATIVE_TO_NOW) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                            <p class="text-sm text-gray-600">🕐 {{ $event->start_datetime->format('M d, Y H:i') }} - {{ $event->end_datetime->format('H:i') }}</p>
                            
                            @php
                                $capacity = $event->max_attendees ?? $event->max_participants;
                                $percent = $capacity ? round(($event->current_attendees / $capacity) * 100) : 0;
                            @endphp
                            <div class="mt-3 mb-3">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span>Registered: {{ $event->current_attendees }} / {{ $capacity ?? 'Unlimited' }}</span>
                                    <span class="font-semibold">{{ $capacity ? $percent.'%' : '-' }}</span>
                                </div>
                                @if($capacity)
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                                </div>
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
                                <a href="{{ route('check-in.index', $event) }}" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Check-in
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Events by Status Tabs -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b border-gray-200">
                    <div class="flex gap-6 p-6">
                        <button onclick="showTab('open')" id="tab-open" class="text-green-600 border-b-2 border-green-600 pb-2 font-semibold">
                            🟢 Open ({{ $openEvents->count() }})
                        </button>
                        <button onclick="showTab('ongoing')" id="tab-ongoing" class="text-blue-600 border-b-2 border-transparent pb-2 font-semibold">
                            🔵 Ongoing ({{ $ongoingEvents->count() }})
                        </button>
                        <button onclick="showTab('completed')" id="tab-completed" class="text-gray-600 border-b-2 border-transparent pb-2 font-semibold">
                            ⚪ Completed ({{ $completedEvents->count() }})
                        </button>
                    </div>
                </div>

                <!-- Open Events Tab -->
                <div id="content-open" class="p-6">
                    @forelse($openEvents as $event)
                        @php
                            $capacity = $event->max_attendees ?? $event->max_participants;
                            $percent = $capacity ? round(($event->current_attendees / $capacity) * 100) : 0;
                        @endphp
                        <div class="border rounded-lg p-4 mb-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                                    <p class="text-sm text-gray-600">🕐 {{ $event->start_datetime->format('M d, Y H:i') }}</p>
                                    
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span>Attendees: {{ $event->current_attendees }} / {{ $capacity ?? 'Unlimited' }}</span>
                                            <span class="font-semibold">{{ $capacity ? $percent.'%' : '-' }}</span>
                                        </div>
                                        @if($capacity)
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('check-in.index', $event) }}" class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Check-in
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No open events</p>
                    @endforelse
                </div>

                <!-- Ongoing Events Tab -->
                <div id="content-ongoing" class="p-6 hidden">
                    @forelse($ongoingEvents as $event)
                        @php
                            $capacity = $event->max_attendees ?? $event->max_participants;
                            $percent = $capacity ? round(($event->current_attendees / $capacity) * 100) : 0;
                        @endphp
                        <div class="border rounded-lg p-4 mb-4 border-blue-300 bg-blue-50 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">🔴 {{ $event->title }} (LIVE NOW)</h4>
                                    <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                                    <p class="text-sm text-gray-600">🕐 {{ $event->start_datetime->format('M d, Y H:i') }} - {{ $event->end_datetime->format('H:i') }}</p>
                                    
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span>Checked In: {{ $event->registrations->where('status', 'checked_in')->count() }} / {{ $event->registrations->count() }} registered</span>
                                        </div>
                                        @php
                                            $checkedIn = $event->registrations->where('status', 'checked_in')->count();
                                            $registered = $event->registrations->count();
                                            $checkInPercent = $registered > 0 ? round(($checkedIn / $registered) * 100) : 0;
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $checkInPercent }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('check-in.index', $event) }}" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 font-semibold animate-pulse">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Live Check-in
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No ongoing events</p>
                    @endforelse
                </div>

                <!-- Completed Events Tab -->
                <div id="content-completed" class="p-6 hidden">
                    @forelse($completedEvents->take(10) as $event)
                        @php
                            $checkedIn = $event->registrations->where('status', 'checked_in')->count();
                            $noShow = $event->registrations->where('status', 'no_show')->count();
                            $registered = $event->registrations->count();
                        @endphp
                        <div class="border rounded-lg p-4 mb-4 bg-gray-50 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->title }}</h4>
                                    <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                                    <p class="text-sm text-gray-600">🕐 {{ $event->start_datetime->format('M d, Y H:i') }}</p>
                                    
                                    <div class="mt-3 grid grid-cols-3 gap-2 text-sm">
                                        <div class="bg-white p-2 rounded">
                                            <p class="text-gray-600">Registered</p>
                                            <p class="text-xl font-bold text-blue-600">{{ $registered }}</p>
                                        </div>
                                        <div class="bg-white p-2 rounded">
                                            <p class="text-gray-600">Checked In</p>
                                            <p class="text-xl font-bold text-green-600">{{ $checkedIn }}</p>
                                        </div>
                                        <div class="bg-white p-2 rounded">
                                            <p class="text-gray-600">No Show</p>
                                            <p class="text-xl font-bold text-red-600">{{ $noShow }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                <a href="{{ route('check-in.statistics', $event) }}" class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Statistics
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No completed events</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all content
            document.getElementById('content-open').classList.add('hidden');
            document.getElementById('content-ongoing').classList.add('hidden');
            document.getElementById('content-completed').classList.add('hidden');
            
            // Remove active styling
            document.getElementById('tab-open').classList.remove('border-b-2', 'border-green-600');
            document.getElementById('tab-ongoing').classList.remove('border-b-2', 'border-blue-600');
            document.getElementById('tab-completed').classList.remove('border-b-2', 'border-gray-600');
            
            document.getElementById('tab-open').classList.add('border-b-2', 'border-transparent');
            document.getElementById('tab-ongoing').classList.add('border-b-2', 'border-transparent');
            document.getElementById('tab-completed').classList.add('border-b-2', 'border-transparent');
            
            // Show selected tab
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            // Add active styling
            if (tab === 'open') {
                document.getElementById('tab-open').classList.add('border-b-2', 'border-green-600');
            } else if (tab === 'ongoing') {
                document.getElementById('tab-ongoing').classList.add('border-b-2', 'border-blue-600');
            } else if (tab === 'completed') {
                document.getElementById('tab-completed').classList.add('border-b-2', 'border-gray-600');
            }
        }
    </script>
</x-app-layout>
