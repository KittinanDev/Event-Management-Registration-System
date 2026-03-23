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
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f1f5f9; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        .app-bg { min-height: 100vh; background: linear-gradient(160deg, #0f172a 0%, #0d1f14 50%, #0f172a 100%); }
        .grid-pattern {
            background-image: linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);
            background-size: 40px 40px;
        }
        /* Cards */
        .card { background:rgba(255,255,255,0.04); backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.08); border-radius:1rem; }
        .card-solid { background:#111827; border:1px solid rgba(255,255,255,0.08); border-radius:1rem; }
        /* Inputs */
        .input-field { width:100%; padding:0.625rem 0.875rem; border-radius:0.625rem; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); color:#f1f5f9; font-size:0.875rem; outline:none; transition:border-color 0.2s,box-shadow 0.2s; }
        .input-field::placeholder { color:#64748b; }
        .input-field:focus { border-color:#22c55e; box-shadow:0 0 0 3px rgba(34,197,94,0.15); }
        select.input-field option { background:#1e293b; color:#f1f5f9; }
        textarea.input-field { resize:vertical; }
        .label { display:block; font-size:0.8125rem; font-weight:500; color:#94a3b8; margin-bottom:0.375rem; }
        .error-msg { font-size:0.75rem; color:#f87171; margin-top:0.3rem; }
        /* Buttons */
        .btn-green { display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:700; color:#fff; border:none; cursor:pointer; background:linear-gradient(90deg,#16a34a 30%,#22c55e 50%,#16a34a 70%); background-size:200% auto; transition:background-position 0.4s,box-shadow 0.3s; }
        .btn-green:hover { background-position:right center; box-shadow:0 0 20px rgba(34,197,94,0.4); }
        .btn-slate { display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; color:#cbd5e1; border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.06); cursor:pointer; transition:background 0.2s,color 0.2s; }
        .btn-slate:hover { background:rgba(255,255,255,0.1); color:#fff; }
        .btn-red { display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; color:#fff; background:#dc2626; border:none; cursor:pointer; transition:background 0.2s; }
        .btn-red:hover { background:#b91c1c; }
        .btn-amber { display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; color:#fff; background:#d97706; border:none; cursor:pointer; transition:background 0.2s; }
        .btn-amber:hover { background:#b45309; }
        .btn-purple { display:inline-flex; align-items:center; gap:0.375rem; padding:0.625rem 1.25rem; border-radius:0.625rem; font-size:0.875rem; font-weight:600; color:#fff; background:#7c3aed; border:none; cursor:pointer; transition:background 0.2s; }
        .btn-purple:hover { background:#6d28d9; }
        /* Chips */
        .chip { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:999px; font-size:0.7rem; font-weight:700; letter-spacing:0.04em; text-transform:uppercase; }
        .chip-open    { background:rgba(34,197,94,0.15); color:#4ade80; border:1px solid rgba(34,197,94,0.3); }
        .chip-ongoing { background:rgba(234,179,8,0.15); color:#fbbf24; border:1px solid rgba(234,179,8,0.3); }
        .chip-closed  { background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.3); }
        .chip-completed { background:rgba(99,102,241,0.15); color:#a5b4fc; border:1px solid rgba(99,102,241,0.3); }
        .chip-cancelled { background:rgba(100,116,139,0.2); color:#94a3b8; border:1px solid rgba(100,116,139,0.3); }
        .chip-draft   { background:rgba(100,116,139,0.2); color:#94a3b8; border:1px solid rgba(100,116,139,0.3); }
        .chip-registered { background:rgba(59,130,246,0.15); color:#93c5fd; border:1px solid rgba(59,130,246,0.3); }
        .chip-checked_in { background:rgba(34,197,94,0.15); color:#4ade80; border:1px solid rgba(34,197,94,0.3); }
        .chip-no_show { background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.3); }
        /* Page header */
        .page-header { border-bottom:1px solid rgba(255,255,255,0.08); background:rgba(15,23,42,0.7); backdrop-filter:blur(12px); }
        /* Table */
        .dark-table { width:100%; border-collapse:collapse; }
        .dark-table thead tr { border-bottom:1px solid rgba(255,255,255,0.10); }
        .dark-table thead th { padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#64748b; }
        .dark-table tbody tr { border-bottom:1px solid rgba(255,255,255,0.05); transition:background 0.15s; }
        .dark-table tbody tr:last-child { border-bottom:none; }
        .dark-table tbody tr:hover { background:rgba(255,255,255,0.03); }
        .dark-table tbody td { padding:0.875rem 1rem; font-size:0.875rem; color:#cbd5e1; }
        /* Alerts */
        .alert-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); border-radius:0.75rem; padding:0.875rem 1rem; color:#4ade80; font-size:0.875rem; margin-bottom:1.25rem; }
        .alert-error   { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); border-radius:0.75rem; padding:0.875rem 1rem; color:#f87171; font-size:0.875rem; margin-bottom:1.25rem; }
        /* Stat card */
        .stat-card { background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:0.875rem; padding:1.25rem; }
        .stat-card .stat-val { font-size:1.875rem; font-weight:800; color:#fff; }
        .stat-card .stat-label { font-size:0.75rem; color:#64748b; margin-top:0.125rem; }
        /* Animations */
        @keyframes pulse-green { 0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.4)} 50%{box-shadow:0 0 0 8px rgba(34,197,94,0)} }
        .badge-pulse { animation:pulse-green 2s ease-in-out infinite; }
        .hover-card { transition:transform 0.2s,box-shadow 0.2s; }
        .hover-card:hover { transform:translateY(-2px); box-shadow:0 8px 32px rgba(0,0,0,0.3); }
        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; height:6px; }
        ::-webkit-scrollbar-track { background:#0f172a; }
        ::-webkit-scrollbar-thumb { background:#16a34a; border-radius:3px; }
    </style>
</head>
<body class="antialiased">
<div class="app-bg relative">
    <div class="grid-pattern fixed inset-0 pointer-events-none opacity-60 z-0"></div>
    <div class="relative z-10">
        @include('layouts.navigation')
        @isset($header)
            <div class="page-header">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                    {{ $header }}
                </div>
            </div>
        @endisset
        <main class="relative">{{ $slot }}</main>
    </div>
</div>
</body>
</html>
