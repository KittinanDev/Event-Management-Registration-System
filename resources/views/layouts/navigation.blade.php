<nav x-data="{ open: false }" class="relative z-50 border-b border-white/8 bg-slate-900/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-green-500 shadow-lg shadow-green-500/30 transition-transform group-hover:scale-105">
                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="text-sm font-bold text-white">EventHub</span>
                    </a>
                </div>

                {{-- Desktop Nav Links --}}
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors
                            {{ request()->routeIs('dashboard') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white hover:bg-white/6' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors
                            {{ request()->routeIs('events.*') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white hover:bg-white/6' }}">
                        Events
                    </a>
                    @auth
                        <a href="{{ route('registrations.my') }}"
                            class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors
                                {{ request()->routeIs('registrations.my') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white hover:bg-white/6' }}">
                            My Registrations
                        </a>
                        @if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin'))
                            <a href="{{ route('admin.calendar') }}"
                                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors
                                    {{ request()->routeIs('admin.calendar') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white hover:bg-white/6' }}">
                                Calendar
                            </a>
                            <a href="{{ route('admin.reports.summary-pdf') }}" target="_blank"
                                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium text-slate-400 hover:text-white hover:bg-white/6 transition-colors">
                                PDF Report
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right: User Menu --}}
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900 border border-white/10 rounded-xl shadow-2xl">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-lg border border-white/12 bg-white/6 px-3 py-2 text-sm font-medium text-slate-300 transition-colors hover:bg-white/10 hover:text-white">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-500/20 text-xs font-bold text-green-400">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            {{ Auth::user()->name }}
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300 hover:bg-white/6 hover:text-white transition-colors">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <div class="my-1 border-t border-white/8"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Log Out
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-400 transition-colors hover:text-white">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-green text-xs px-3 py-2">Get Started</a>
                @endif
            </div>
            @endauth

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-white/8 focus:outline-none transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/8">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white' }}">Dashboard</a>
            <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('events.*') ? 'bg-green-500/15 text-green-400' : 'text-slate-400 hover:text-white' }}">Events</a>
            @auth
                <a href="{{ route('registrations.my') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-400 hover:text-white">My Registrations</a>
                @if(Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin'))
                    <a href="{{ route('admin.calendar') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-400 hover:text-white">Calendar</a>
                    <a href="{{ route('admin.reports.summary-pdf') }}" target="_blank" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-400 hover:text-white">PDF Report</a>
                @endif
            @endauth
        </div>
        @auth
        <div class="pt-4 pb-3 border-t border-white/8 px-4">
            <div class="flex items-center gap-3 mb-3">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-500/20 text-sm font-bold text-green-400">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div>
                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm text-slate-400 hover:text-white">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-sm text-red-400 hover:bg-red-500/10">Log Out</button>
                </form>
            </div>
        </div>
        @else
        <div class="pt-3 pb-3 border-t border-white/8 px-4 space-y-1">
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-sm text-slate-400 hover:text-white">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-sm text-slate-400 hover:text-white">Register</a>
            @endif
        </div>
        @endauth
    </div>
</nav>
