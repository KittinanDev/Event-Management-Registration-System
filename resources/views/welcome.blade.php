<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Event Management System') }} — Manage Events Effortlessly</title>
    <meta name="description" content="ระบบจัดการอีเวนต์และลงทะเบียนออนไลน์ครบวงจร Role-based access, real-time check-in, analytics dashboard">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900|plus-jakarta-sans:600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --green: #16a34a;
            --green-light: #22c55e;
            --green-dark: #15803d;
        }
        * { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Animated gradient hero */
        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #0d1f14 40%, #0a2520 70%, #0f172a 100%);
        }
        .hero-glow {
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(34,197,94,0.18) 0%, transparent 70%);
        }

        /* Grid pattern overlay */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Glassmorphism card */
        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.10);
        }
        .glass-green {
            background: rgba(22,163,74,0.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(34,197,94,0.20);
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-1 { animation: float 6s ease-in-out infinite; }
        .float-2 { animation: float 8s ease-in-out infinite 1s; }
        .float-3 { animation: float 7s ease-in-out infinite 2s; }

        /* Shine sweep */
        @keyframes shine {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .btn-shine {
            background: linear-gradient(90deg, var(--green) 30%, #4ade80 50%, var(--green) 70%);
            background-size: 200% auto;
            transition: background-position 0.4s ease, box-shadow 0.3s;
        }
        .btn-shine:hover {
            background-position: right center;
            box-shadow: 0 0 28px rgba(34,197,94,0.45);
        }

        /* Count-up animation trigger */
        @keyframes countUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .stat-number { animation: countUp 0.6s ease forwards; }

        /* Feature card hover */
        .feat-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .feat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.10);
        }

        /* Step connector line */
        .step-line::after {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(100% - 0px);
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #16a34a, transparent);
        }

        /* Badge pulse */
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0.4); }
            50% { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
        }
        .badge-pulse {
            animation: pulse-green 2s ease-in-out infinite;
        }

        /* Section fade-in on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(90deg, #4ade80, #86efac);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #16a34a; border-radius: 3px; }

        /* Nav blur on scroll */
        .nav-scrolled {
            background: rgba(15,23,42,0.95) !important;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        /* Tag chip */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .chip-open { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .chip-ongoing { background: rgba(234,179,8,0.15); color: #fbbf24; border: 1px solid rgba(234,179,8,0.3); }
        .chip-closed { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .chip-draft { background: rgba(100,116,139,0.2); color: #94a3b8; border: 1px solid rgba(100,116,139,0.3); }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased">

    {{-- ===== NAVIGATION ===== --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-green-500 text-sm font-bold text-white shadow-lg shadow-green-500/30 transition-transform group-hover:scale-105">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                <span class="text-sm font-bold tracking-tight text-white">EventHub</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden items-center gap-8 text-sm font-medium text-slate-300 md:flex">
                <a href="#features" class="transition-colors hover:text-white">Features</a>
                <a href="#how-it-works" class="transition-colors hover:text-white">How It Works</a>
                <a href="#events" class="transition-colors hover:text-white">Events</a>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-shine rounded-lg px-4 py-2 text-sm font-semibold text-white">
                            Open Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 transition-colors hover:text-white">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-shine rounded-lg px-4 py-2 text-sm font-semibold text-white">
                                Get Started →
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        {{-- ===== HERO ===== --}}
        <section class="hero-bg relative overflow-hidden">
            <div class="hero-glow absolute inset-0 pointer-events-none"></div>
            <div class="grid-pattern absolute inset-0 pointer-events-none opacity-60"></div>

            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute -top-40 -right-40 h-96 w-96 rounded-full bg-green-500/10 blur-3xl"></div>
            <div class="pointer-events-none absolute top-1/2 -left-32 h-72 w-72 rounded-full bg-emerald-400/8 blur-3xl"></div>

            <div class="relative mx-auto max-w-7xl px-4 pb-20 pt-28 sm:px-6 lg:px-8 lg:pt-36 lg:pb-32">
                <div class="grid items-center gap-12 lg:grid-cols-2">

                    {{-- Left: Copy --}}
                    <div>
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-green-500/30 bg-green-500/10 px-4 py-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-400 badge-pulse"></span>
                            <span class="text-xs font-semibold uppercase tracking-widest text-green-400">Version 1.0 · Live Now</span>
                        </div>

                        <h1 class="font-display text-5xl font-extrabold leading-[1.1] text-white sm:text-6xl lg:text-[4rem]">
                            Organize Events.<br>
                            <span class="text-gradient">Register Smarter.</span>
                        </h1>

                        <p class="mt-6 max-w-lg text-base leading-7 text-slate-400">
                            ระบบจัดการอีเวนต์และลงทะเบียนออนไลน์ครบวงจร ตั้งแต่สร้างกิจกรรม, รับ&shy;ลงทะเบียน, เช็กอินหน้างาน, จนถึง Analytics &amp; PDF Export
                        </p>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <a href="{{ route('events.index') }}" class="btn-shine rounded-xl px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-green-700/30">
                                Browse Events
                                <svg class="ml-1.5 inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                            <a href="{{ route('register') }}" class="group inline-flex items-center gap-2 rounded-xl border border-slate-600 px-6 py-3.5 text-sm font-semibold text-slate-300 transition-all duration-200 hover:border-green-500 hover:text-white">
                                Create Account
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        {{-- Trust bar --}}
                        <div class="mt-10 flex items-center gap-6 text-xs text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Role-based access
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Real-time check-in
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                PDF reports
                            </span>
                        </div>
                    </div>

                    {{-- Right: Stats dashboard card --}}
                    <div class="relative hidden lg:block">
                        {{-- Main card --}}
                        <div class="glass rounded-2xl p-6 float-1">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Live Stats</span>
                                <span class="flex items-center gap-1.5 text-xs text-green-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-400 badge-pulse"></span>
                                    Live
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="glass-green rounded-xl p-4">
                                    <p class="text-2xl font-extrabold text-white stat-number">{{ \App\Models\Event::count() }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Total Events</p>
                                </div>
                                <div class="glass-green rounded-xl p-4">
                                    <p class="text-2xl font-extrabold text-white stat-number">{{ \App\Models\Registration::count() }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Registrations</p>
                                </div>
                                <div class="glass-green rounded-xl p-4">
                                    <p class="text-2xl font-extrabold text-white stat-number">{{ \App\Models\Registration::where('status','checked_in')->count() }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Checked-in</p>
                                </div>
                                <div class="glass-green rounded-xl p-4">
                                    <p class="text-2xl font-extrabold text-white stat-number">{{ \App\Models\User::count() }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">Users</p>
                                </div>
                            </div>
                            <div class="mt-4 rounded-xl border border-green-500/20 bg-green-500/10 p-3 text-xs text-green-300">
                                <span class="font-semibold">Demo:</span> admin@example.com / password
                            </div>
                        </div>

                        {{-- Floating mini cards --}}
                        <div class="glass absolute -top-6 -right-6 rounded-xl p-3 float-2">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-green-500/20">
                                    <svg class="h-4 w-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-white">Check-in Open</p>
                                    <p class="text-[10px] text-slate-400">{{ \App\Models\Event::where('status','ongoing')->count() }} events ongoing</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass absolute -bottom-6 -left-8 rounded-xl p-3 float-3">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-amber-400/20">
                                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div>
                                    <p class="text-xs font-semibold text-white">Open for Registration</p>
                                    <p class="text-[10px] text-slate-400">{{ \App\Models\Event::where('status','open')->count() }} events available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wave divider --}}
            <div class="absolute bottom-0 left-0 right-0 overflow-hidden">
                <svg class="relative block w-full" style="height:48px" viewBox="0 0 1200 48" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,48 L0,24 Q150,0 300,24 Q450,48 600,24 Q750,0 900,24 Q1050,48 1200,24 L1200,48 Z" fill="white"/>
                </svg>
            </div>
        </section>

        {{-- ===== STATS STRIP ===== --}}
        <section class="border-y border-slate-100 bg-white py-10 reveal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 gap-6 text-center sm:grid-cols-4">
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ \App\Models\Event::count() }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Events Created</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ \App\Models\Registration::count() }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Registrations</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ \App\Models\Registration::where('status','checked_in')->count() }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Successful Check-ins</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-slate-900 sm:text-4xl">{{ \App\Models\User::count() }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">Registered Users</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== FEATURES ===== --}}
        <section id="features" class="bg-slate-50 py-20 reveal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-14 text-center">
                    <p class="mb-3 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-green-700">Features</p>
                    <h2 class="font-display text-3xl font-extrabold text-slate-900 sm:text-4xl">ครบทุก Flow ของการจัดงาน</h2>
                    <p class="mx-auto mt-4 max-w-xl text-base text-slate-600">จากสร้างอีเวนต์ไปจนถึง export รายงาน ทุกขั้นตอนอยู่ใน platform เดียว</p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Feature 1 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Role-Based Access</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">แยกสิทธิ์ Admin, Organizer และ Attendee อย่างชัดเจน ด้วย Spatie Permission พร้อม Policy ทุก action</p>
                    </div>

                    {{-- Feature 2 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Event Management</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">สร้าง แก้ไข และลบ Event พร้อมกำหนดสถานะ (Draft → Open → Ongoing → Closed) และจำนวนที่นั่งสูงสุด</p>
                    </div>

                    {{-- Feature 3 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Registration System</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">ลงทะเบียนเข้าร่วม Event พร้อม unique registration code ตรวจสอบสถานะ และยกเลิกได้ก่อนงาน</p>
                    </div>

                    {{-- Feature 4 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Check-in System</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Organizer เช็กอิน Attendee หน้างาน หรือ Attendee เช็กอินด้วยตัวเอง ล็อกตามช่วงเวลาของ Event</p>
                    </div>

                    {{-- Feature 5 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100">
                            <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Analytics Dashboard</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Admin dashboard แสดงกราฟ Bar, Line, Doughnut สรุปสถิติ Event และ Registration แบบ real-time</p>
                    </div>

                    {{-- Feature 6 --}}
                    <div class="feat-card rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-teal-100">
                            <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">PDF Export</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Export รายงานสรุป Event ทั้งหมดเป็น PDF ด้วย DomPDF พร้อมพิมพ์และแชร์ได้ทันที</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== HOW IT WORKS ===== --}}
        <section id="how-it-works" class="bg-white py-20 reveal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-14 text-center">
                    <p class="mb-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-slate-600">Workflow</p>
                    <h2 class="font-display text-3xl font-extrabold text-slate-900 sm:text-4xl">3 ขั้นตอน เริ่มจัดงานได้เลย</h2>
                </div>

                <div class="grid gap-8 sm:grid-cols-3">
                    <div class="text-center relative">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border-2 border-green-200 bg-green-50">
                            <span class="text-2xl font-black text-green-600">1</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">สร้าง Event</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Organizer หรือ Admin สร้างอีเวนต์ กำหนดวันเวลา สถานที่ จำนวนที่นั่ง แล้วเปิดรับสมัคร</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border-2 border-blue-200 bg-blue-50">
                            <span class="text-2xl font-black text-blue-600">2</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">ลงทะเบียน</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Attendee ค้นหาอีเวนต์ที่สนใจ กด Register รับ Code ยืนยัน และดูสถานะได้จาก dashboard</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border-2 border-purple-200 bg-purple-50">
                            <span class="text-2xl font-black text-purple-600">3</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">เช็กอิน &amp; Report</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">วันงาน เช็กอินหน้างาน ระบบบันทึก Attendance อัตโนมัติ Admin ดูสถิติและ export รายงาน PDF</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== UPCOMING EVENTS ===== --}}
        <section id="events" class="bg-slate-50 py-20 reveal">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="mb-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold uppercase tracking-widest text-green-700">Upcoming</p>
                        <h2 class="font-display text-3xl font-extrabold text-slate-900">Upcoming Events</h2>
                        <p class="mt-2 text-sm text-slate-600">อีเวนต์ที่กำลังจะมาถึง — ลงทะเบียนได้เลย</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="group flex items-center gap-1 text-sm font-semibold text-green-700 transition-colors hover:text-green-800">
                        View all events
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                @php
                    $upcomingEvents = \App\Models\Event::where('start_datetime', '>', now())
                        ->orderBy('start_datetime')
                        ->limit(6)
                        ->get();
                @endphp

                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @forelse ($upcomingEvents as $event)
                        <article class="feat-card group flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            {{-- Color top stripe based on status --}}
                            <div class="h-1.5 w-full {{ $event->status === 'open' ? 'bg-green-500' : ($event->status === 'ongoing' ? 'bg-amber-400' : 'bg-slate-300') }}"></div>
                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-3 flex items-center justify-between">
                                    @php
                                        $chipClass = match($event->status) {
                                            'open' => 'chip-open',
                                            'ongoing' => 'chip-ongoing',
                                            'closed' => 'chip-closed',
                                            default => 'chip-draft',
                                        };
                                    @endphp
                                    <span class="chip {{ $chipClass }}">
                                        <span class="h-1 w-1 rounded-full bg-current"></span>
                                        {{ $event->status }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-400">
                                        {{ optional($event->start_datetime)->format('d M Y') }}
                                    </span>
                                </div>

                                <h3 class="line-clamp-2 text-base font-bold text-slate-900 group-hover:text-green-700 transition-colors">{{ $event->title }}</h3>
                                <p class="mt-2 line-clamp-2 flex-1 text-sm leading-6 text-slate-600">{{ $event->description }}</p>

                                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <span class="flex items-center gap-1.5 text-xs text-slate-500">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ Str::limit($event->location, 24) }}
                                    </span>
                                    <a href="{{ route('events.show', $event->id) }}" class="text-xs font-semibold text-green-700 transition-colors hover:text-green-800">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="mt-3 text-sm text-slate-500">ยังไม่มีอีเวนต์ที่กำลังจะมาถึง</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ===== CTA BANNER ===== --}}
        <section class="reveal">
            <div class="hero-bg relative overflow-hidden py-20">
                <div class="hero-glow absolute inset-0 pointer-events-none opacity-60"></div>
                <div class="grid-pattern absolute inset-0 pointer-events-none opacity-40"></div>
                <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
                    <h2 class="font-display text-3xl font-extrabold text-white sm:text-4xl">พร้อมจัดงานแล้วหรือยัง?</h2>
                    <p class="mx-auto mt-4 max-w-xl text-base text-slate-400">สร้างบัญชีฟรี เริ่มสร้าง Event แรกของคุณได้เลย หรือเข้าสู่ระบบด้วย admin account เพื่อทดสอบฟีเจอร์ทั้งหมด</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-shine rounded-xl px-8 py-4 text-sm font-bold text-white shadow-xl shadow-green-900/40">
                                Open Dashboard →
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-shine rounded-xl px-8 py-4 text-sm font-bold text-white shadow-xl shadow-green-900/40">
                                Create Free Account →
                            </a>
                            <a href="{{ route('login') }}" class="rounded-xl border border-slate-600 px-8 py-4 text-sm font-semibold text-slate-300 transition-all hover:border-green-500 hover:text-white">
                                Log in
                            </a>
                        @endauth
                    </div>
                    <p class="mt-6 text-xs text-slate-500">Demo: admin@example.com · password (full admin access)</p>
                </div>
            </div>
        </section>
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="hero-bg border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-green-500 text-sm font-bold text-white shadow-lg shadow-green-500/30">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="text-sm font-bold text-white">EventHub</span>
                    </a>
                    <p class="mt-3 text-xs leading-5 text-slate-400">ระบบจัดการอีเวนต์และลงทะเบียนออนไลน์ ครบวงจร สำหรับองค์กรและสถานศึกษา</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Platform</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('events.index') }}" class="text-sm text-slate-400 transition-colors hover:text-white">Browse Events</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-sm text-slate-400 transition-colors hover:text-white">Dashboard</a></li>
                        <li><a href="#features" class="text-sm text-slate-400 transition-colors hover:text-white">Features</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Account</h4>
                    <ul class="space-y-2.5">
                        @auth
                            <li><a href="{{ route('profile.edit') }}" class="text-sm text-slate-400 transition-colors hover:text-white">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm text-slate-400 transition-colors hover:text-white">Log out</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-sm text-slate-400 transition-colors hover:text-white">Log in</a></li>
                            <li><a href="{{ route('register') }}" class="text-sm text-slate-400 transition-colors hover:text-white">Register</a></li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Tech Stack</h4>
                    <ul class="space-y-2.5">
                        <li class="text-sm text-slate-400">Laravel 12</li>
                        <li class="text-sm text-slate-400">TailwindCSS v3</li>
                        <li class="text-sm text-slate-400">Spatie Permission</li>
                        <li class="text-sm text-slate-400">Chart.js + DomPDF</li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-8 flex flex-col items-center justify-between gap-3 sm:flex-row">
                <p class="text-xs text-slate-500">© {{ now()->year }} Event Management Registration System. Built with Laravel.</p>
                <div class="flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 badge-pulse"></span>
                    <span class="text-xs text-slate-500">System operational</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar: change style on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('nav-scrolled', window.scrollY > 20);
        });
        // Set initial state
        navbar.classList.toggle('nav-scrolled', window.scrollY > 20);

        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(el => observer.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
