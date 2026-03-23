<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EventHub') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|plus-jakarta-sans:700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-bg {
            background: linear-gradient(135deg, #0f172a 0%, #0d1f14 40%, #0a2520 70%, #0f172a 100%);
        }
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.09);
        }
        .input-dark {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border-radius: 0.625rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #f1f5f9;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-dark::placeholder { color: #64748b; }
        .input-dark:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.15);
        }
        .label-dark {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 0.375rem;
        }
        .btn-primary {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(90deg, #16a34a 30%, #22c55e 50%, #16a34a 70%);
            background-size: 200% auto;
            transition: background-position 0.4s ease, box-shadow 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-position: right center;
            box-shadow: 0 0 24px rgba(34,197,94,0.4);
        }
        .error-msg { font-size: 0.75rem; color: #f87171; margin-top: 0.3rem; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 6s ease-in-out infinite; }
        @keyframes pulse-green {
            0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.4)}
            50%{box-shadow:0 0 0 8px rgba(34,197,94,0)}
        }
        .badge-pulse { animation: pulse-green 2s ease-in-out infinite; }
        .text-gradient {
            background: linear-gradient(90deg,#4ade80,#86efac);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
    </style>
</head>
<body class="antialiased">
<div class="hero-bg relative min-h-screen">
    <div class="grid-pattern absolute inset-0 pointer-events-none opacity-50"></div>
    <div class="pointer-events-none absolute -top-32 -right-32 h-80 w-80 rounded-full bg-green-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 -left-24 h-64 w-64 rounded-full bg-emerald-400/8 blur-3xl"></div>

    {{-- Navbar --}}
    <nav class="relative z-10 flex h-16 items-center justify-between px-6 lg:px-12">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-green-500 shadow-lg shadow-green-500/30 transition-transform group-hover:scale-105">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </span>
            <span class="text-sm font-bold text-white">EventHub</span>
        </a>
        <a href="{{ url('/') }}" class="flex items-center gap-1.5 text-xs font-medium text-slate-400 transition-colors hover:text-white">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to home
        </a>
    </nav>

    {{-- Main content --}}
    <div class="relative z-10 flex min-h-[calc(100vh-4rem)] items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
