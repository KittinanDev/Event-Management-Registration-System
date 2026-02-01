<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Event Statistics</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                        <p class="text-gray-600 mt-2">{{ $event->start_datetime->format('M d, Y H:i') }} - {{ $event->end_datetime->format('H:i') }}</p>
                        <p class="text-gray-600">📍 {{ $event->location }}</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Total Registered</p>
                                    <p class="text-4xl font-bold text-blue-600">{{ $totalRegistrations }}</p>
                                </div>
                                <div class="text-5xl text-blue-500">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Checked In</p>
                                    <p class="text-4xl font-bold text-green-600">{{ $checkedIn }}</p>
                                </div>
                                <div class="text-5xl text-green-500">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-50 rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Check-in Rate</p>
                                    <p class="text-4xl font-bold text-purple-600">{{ $checkInRate }}%</p>
                                </div>
                                <div class="text-5xl text-purple-500">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Visualization -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Overview</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">
                                            Checked In
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-semibold inline-block text-green-600">
                                            {{ $checkedIn }} / {{ $totalRegistrations }}
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-6 mb-4 text-xs flex rounded bg-green-200">
                                    <div style="width:{{ $checkInRate }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-600 transition-all duration-500"></div>
                                </div>
                            </div>

                            @php
                                $noShow = $totalRegistrations - $checkedIn;
                                $noShowRate = $totalRegistrations > 0 ? round(($noShow / $totalRegistrations) * 100, 2) : 0;
                            @endphp

                            <div class="relative pt-1 mt-6">
                                <div class="flex mb-2 items-center justify-between">
                                    <div>
                                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">
                                            No Show
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-semibold inline-block text-red-600">
                                            {{ $noShow }} / {{ $totalRegistrations }}
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-hidden h-6 mb-4 text-xs flex rounded bg-red-200">
                                    <div style="width:{{ $noShowRate }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-600 transition-all duration-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Event Details</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold">{{ ucfirst($event->status) }}</span>
                                </div>
                                @php
                                    $capacity = $event->max_attendees ?? $event->max_participants;
                                @endphp
                                @if($capacity)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Capacity:</span>
                                    <span class="font-semibold">{{ $capacity }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Occupancy Rate:</span>
                                    <span class="font-semibold">{{ round(($totalRegistrations / $capacity) * 100, 2) }}%</span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-semibold">{{ $event->start_datetime->diffInHours($event->end_datetime) }} hours</span>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Attendance Summary</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Registered:</span>
                                    <span class="font-semibold text-blue-600">{{ $totalRegistrations }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Checked In:</span>
                                    <span class="font-semibold text-green-600">{{ $checkedIn }} ({{ $checkInRate }}%)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No Show:</span>
                                    <span class="font-semibold text-red-600">{{ $noShow }} ({{ $noShowRate }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Event
                        </a>
                        <a href="{{ route('check-in.index', $event) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Check-in Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
