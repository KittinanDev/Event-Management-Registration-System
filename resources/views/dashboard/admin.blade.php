<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Admin</p>
                <h2 class="text-xl font-bold text-white leading-tight">Admin Dashboard</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.calendar') }}" class="btn-purple inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Calendar
                </a>
                <a href="{{ route('admin.reports.summary-pdf') }}" class="btn-slate inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('events.create') }}" class="btn-green inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Total Events</p>
                            <p class="text-3xl font-bold text-white">{{ $totalEvents }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Total Registrations</p>
                            <p class="text-3xl font-bold text-white">{{ $totalRegistrations }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Checked In</p>
                            <p class="text-3xl font-bold text-green-400">{{ $totalCheckedIn }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">No Show</p>
                            <p class="text-3xl font-bold text-red-400">{{ $totalNoShow }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Events by Status</h3>
                    <div class="w-full max-w-sm h-40 mx-auto">
                        <canvas id="eventsStatusBarChart"></canvas>
                    </div>
                </div>
                <div class="card p-5">
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Registrations Trend</h3>
                    <div class="w-full max-w-sm h-40 mx-auto">
                        <canvas id="registrationsLineChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card p-5">
                <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Registration Status</h3>
                <div class="w-full max-w-xs h-44 mx-auto">
                    <canvas id="registrationDoughnutChart"></canvas>
                </div>
            </div>

            {{-- Upcoming Events (Next 7 Days) --}}
            @if($upcomingEvents->count() > 0)
            <div class="card overflow-hidden">
                <div class="p-5 border-b border-white/5">
                    <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Upcoming Events (Next 7 Days)</h3>
                </div>
                <div class="p-5 space-y-4">
                    @foreach($upcomingEvents as $event)
                    @php
                        $capacity = $event->max_attendees ?? $event->max_participants;
                        $percent = $capacity ? round(($event->current_attendees / $capacity) * 100) : 0;
                    @endphp
                    <div class="bg-white/3 rounded-xl p-4 border border-white/5">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-semibold text-white">{{ $event->title }}</h4>
                            <span class="chip-open text-xs ml-2 shrink-0">
                                {{ $event->start_datetime->diffForHumans(now(), \Carbon\Carbon::DIFF_RELATIVE_TO_NOW) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">{{ $event->location }}</p>
                        <p class="text-xs text-slate-500 mb-3">{{ $event->start_datetime->format('M d, Y H:i') }} &ndash; {{ $event->end_datetime->format('H:i') }}</p>
                        @if($capacity)
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-slate-500 mb-1">
                                <span>{{ $event->current_attendees }} / {{ $capacity }}</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-white/5">
                                <div class="h-1.5 rounded-full bg-blue-500 transition-all" style="width:{{ $percent }}%"></div>
                            </div>
                        </div>
                        @endif
                        <div class="flex gap-2">
                            <a href="{{ route('events.show', $event) }}" class="btn-slate text-xs py-1 px-2.5">View</a>
                            <a href="{{ route('check-in.index', $event) }}" class="btn-purple text-xs py-1 px-2.5">Check-in</a>
                            <a href="{{ route('events.edit', $event) }}" class="btn-amber text-xs py-1 px-2.5">Edit</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-red text-xs py-1 px-2.5">Delete</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Events by Status Tabs --}}
            <div class="card overflow-hidden">
                <div class="border-b border-white/5">
                    <div class="flex gap-1 p-4">
                        <button onclick="showTab('open')" id="tab-open" class="px-4 py-2 rounded-lg text-sm font-semibold text-green-400 bg-green-500/10 transition">
                            Open ({{ $openEvents->count() }})
                        </button>
                        <button onclick="showTab('ongoing')" id="tab-ongoing" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:bg-white/5 transition">
                            Ongoing ({{ $ongoingEvents->count() }})
                        </button>
                        <button onclick="showTab('completed')" id="tab-completed" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:bg-white/5 transition">
                            Completed ({{ $completedEvents->count() }})
                        </button>
                    </div>
                </div>

                {{-- Open Events Tab --}}
                <div id="content-open" class="p-5 space-y-3">
                    @forelse($openEvents as $event)
                        @php
                            $capacity = $event->max_attendees ?? $event->max_participants;
                            $percent = $capacity ? round(($event->current_attendees / $capacity) * 100) : 0;
                        @endphp
                        <div class="bg-white/3 rounded-xl p-4 border border-white/5">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-semibold text-white">{{ $event->title }}</h4>
                                <span class="chip-open text-xs ml-2 shrink-0">Open</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1">{{ $event->location }}</p>
                            <p class="text-xs text-slate-500 mb-3">{{ $event->start_datetime->format('M d, Y H:i') }}</p>
                            @if($capacity)
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>{{ $event->current_attendees }} / {{ $capacity }}</span>
                                    <span>{{ $percent }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-white/5">
                                    <div class="h-1.5 rounded-full bg-green-500 transition-all" style="width:{{ $percent }}%"></div>
                                </div>
                            </div>
                            @endif
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('events.show', $event) }}" class="btn-slate text-xs py-1 px-2.5">View</a>
                                <a href="{{ route('check-in.index', $event) }}" class="btn-purple text-xs py-1 px-2.5">Check-in</a>
                                <a href="{{ route('events.edit', $event) }}" class="btn-amber text-xs py-1 px-2.5">Edit</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Delete this event?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-red text-xs py-1 px-2.5">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-8">No open events</p>
                    @endforelse
                </div>

                {{-- Ongoing Events Tab --}}
                <div id="content-ongoing" class="p-5 space-y-3 hidden">
                    @forelse($ongoingEvents as $event)
                        @php
                            $checkedIn = $event->registrations->where('status', 'checked_in')->count();
                            $registered = $event->registrations->count();
                            $checkInPercent = $registered > 0 ? round(($checkedIn / $registered) * 100) : 0;
                        @endphp
                        <div class="bg-blue-500/5 rounded-xl p-4 border border-blue-500/20">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-semibold text-white flex items-center gap-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                                    {{ $event->title }} <span class="text-xs text-red-400">LIVE</span>
                                </h4>
                                <span class="chip-ongoing text-xs ml-2 shrink-0">Ongoing</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1">{{ $event->location }}</p>
                            <p class="text-xs text-slate-500 mb-3">{{ $event->start_datetime->format('M d, Y H:i') }} &ndash; {{ $event->end_datetime->format('H:i') }}</p>
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-slate-500 mb-1">
                                    <span>Checked In: {{ $checkedIn }} / {{ $registered }}</span>
                                    <span>{{ $checkInPercent }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-white/5">
                                    <div class="h-1.5 rounded-full bg-blue-400 transition-all" style="width:{{ $checkInPercent }}%"></div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $event) }}" class="btn-slate text-xs py-1 px-2.5">View</a>
                                <a href="{{ route('check-in.index', $event) }}" class="btn-red text-xs py-1 px-2.5 animate-pulse">Live Check-in</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-8">No ongoing events</p>
                    @endforelse
                </div>

                {{-- Completed Events Tab --}}
                <div id="content-completed" class="p-5 space-y-3 hidden">
                    @forelse($completedEvents->take(10) as $event)
                        @php
                            $checkedIn = $event->registrations->where('status', 'checked_in')->count();
                            $noShow = $event->registrations->where('status', 'no_show')->count();
                            $registered = $event->registrations->count();
                        @endphp
                        <div class="bg-white/3 rounded-xl p-4 border border-white/5">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-sm font-semibold text-white">{{ $event->title }}</h4>
                                <span class="chip-completed text-xs ml-2 shrink-0">Completed</span>
                            </div>
                            <p class="text-xs text-slate-500 mb-1">{{ $event->location }}</p>
                            <p class="text-xs text-slate-500 mb-3">{{ $event->start_datetime->format('M d, Y H:i') }}</p>
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                <div class="bg-white/5 rounded-lg p-2 text-center">
                                    <p class="text-xs text-slate-500">Registered</p>
                                    <p class="text-lg font-bold text-blue-400">{{ $registered }}</p>
                                </div>
                                <div class="bg-white/5 rounded-lg p-2 text-center">
                                    <p class="text-xs text-slate-500">Checked In</p>
                                    <p class="text-lg font-bold text-green-400">{{ $checkedIn }}</p>
                                </div>
                                <div class="bg-white/5 rounded-lg p-2 text-center">
                                    <p class="text-xs text-slate-500">No Show</p>
                                    <p class="text-lg font-bold text-red-400">{{ $noShow }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('events.show', $event) }}" class="btn-slate text-xs py-1 px-2.5">View Details</a>
                                <a href="{{ route('check-in.statistics', $event) }}" class="btn-slate text-xs py-1 px-2.5">Statistics</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-8">No completed events</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>


    <script>
        function showTab(tab) {
            ['open', 'ongoing', 'completed'].forEach(t => {
                document.getElementById('content-' + t).classList.add('hidden');
                document.getElementById('tab-' + t).classList.remove('text-green-400', 'bg-green-500/10', 'text-blue-400', 'bg-blue-500/10', 'text-white', 'bg-white/10');
                document.getElementById('tab-' + t).classList.add('text-slate-400');
            });
            document.getElementById('content-' + tab).classList.remove('hidden');
            const colors = { open: ['text-green-400', 'bg-green-500/10'], ongoing: ['text-blue-400', 'bg-blue-500/10'], completed: ['text-white', 'bg-white/10'] };
            colors[tab].forEach(cls => document.getElementById('tab-' + tab).classList.add(cls));
            document.getElementById('tab-' + tab).classList.remove('text-slate-400');
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const chartData = @json($chartData);
        const darkTick = { color: '#64748b', font: { size: 11 } };
        const darkGrid = { color: 'rgba(255,255,255,0.05)' };

        new Chart(document.getElementById('eventsStatusBarChart'), {
            type: 'bar',
            data: {
                labels: chartData.eventsByStatus.labels,
                datasets: [{
                    label: 'Events',
                    data: chartData.eventsByStatus.values,
                    backgroundColor: ['#16a34a', '#2563eb', '#f59e0b', '#6b7280', '#dc2626'],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { ...darkTick, precision: 0 }, grid: darkGrid },
                    x: { ticks: darkTick, grid: darkGrid }
                },
                plugins: { legend: { display: false } }
            }
        });

        new Chart(document.getElementById('registrationsLineChart'), {
            type: 'line',
            data: {
                labels: chartData.monthlyRegistrations.labels,
                datasets: [{
                    label: 'Registrations',
                    data: chartData.monthlyRegistrations.values,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.12)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { ...darkTick, precision: 0 }, grid: darkGrid },
                    x: { ticks: darkTick, grid: darkGrid }
                },
                plugins: { legend: { labels: { color: '#94a3b8' } } }
            }
        });

        new Chart(document.getElementById('registrationDoughnutChart'), {
            type: 'doughnut',
            data: {
                labels: chartData.registrationStatus.labels,
                datasets: [{
                    data: chartData.registrationStatus.values,
                    backgroundColor: ['#3b82f6', '#22c55e', '#ef4444', '#f59e0b'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', padding: 16 } } }
            }
        });
    </script>
</x-app-layout>
