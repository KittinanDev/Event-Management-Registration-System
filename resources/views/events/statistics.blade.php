<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Event Statistics</p>
                <h2 class="text-xl font-bold text-white leading-tight">{{ $event->title }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('check-in.index', $event) }}" class="btn-purple inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Check-in Page
                </a>
                <a href="{{ route('events.show', $event) }}" class="btn-slate inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Event Meta --}}
            <div class="card p-5">
                <div class="flex flex-wrap gap-x-6 gap-y-1 text-sm text-slate-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $event->location }}
                    </span>
                    <span>{{ $event->start_datetime->format('M d, Y H:i') }} &ndash; {{ $event->end_datetime->format('H:i') }}</span>
                    <span class="chip-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                </div>
            </div>

            {{-- Summary Stat Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Total Registered</p>
                            <p class="text-4xl font-bold text-blue-400">{{ $totalRegistrations }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Checked In</p>
                            <p class="text-4xl font-bold text-green-400">{{ $checkedIn }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-green-500/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="label text-xs uppercase tracking-wider mb-1">Check-in Rate</p>
                            <p class="text-4xl font-bold text-purple-400">{{ $checkInRate }}%</p>
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-purple-500/10 flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $noShow = $totalRegistrations - $checkedIn;
                $noShowRate = $totalRegistrations > 0 ? round(($noShow / $totalRegistrations) * 100, 2) : 0;
                $capacity = $event->max_attendees ?? $event->max_participants;
            @endphp

            {{-- Attendance Bars --}}
            <div class="card p-6 space-y-5">
                <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Attendance Overview</h3>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="chip-checked_in text-xs">Checked In</span>
                        <span class="text-slate-400 text-xs">{{ $checkedIn }} / {{ $totalRegistrations }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-white/5">
                        <div class="h-3 rounded-full bg-gradient-to-r from-green-500 to-emerald-400 transition-all duration-500" style="width:{{ $checkInRate }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="chip-no_show text-xs">No Show</span>
                        <span class="text-slate-400 text-xs">{{ $noShow }} / {{ $totalRegistrations }}</span>
                    </div>
                    <div class="h-3 rounded-full bg-white/5">
                        <div class="h-3 rounded-full bg-gradient-to-r from-red-600 to-red-400 transition-all duration-500" style="width:{{ $noShowRate }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Detailed Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="card p-5">
                    <h4 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Event Details</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="label">Status</span>
                            <span class="chip-{{ $event->status }}">{{ ucfirst($event->status) }}</span>
                        </div>
                        @if($capacity)
                        <div class="flex justify-between">
                            <span class="label">Capacity</span>
                            <span class="font-semibold text-white">{{ $capacity }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="label">Occupancy Rate</span>
                            <span class="font-semibold text-white">{{ round(($totalRegistrations / $capacity) * 100, 2) }}%</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="label">Duration</span>
                            <span class="font-semibold text-white">{{ $event->start_datetime->diffInHours($event->end_datetime) }}h</span>
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h4 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4">Attendance Summary</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="label">Total Registered</span>
                            <span class="font-bold text-blue-400">{{ $totalRegistrations }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="label">Checked In</span>
                            <span class="font-bold text-green-400">{{ $checkedIn }} ({{ $checkInRate }}%)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="label">No Show</span>
                            <span class="font-bold text-red-400">{{ $noShow }} ({{ $noShowRate }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
