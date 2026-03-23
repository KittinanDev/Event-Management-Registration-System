<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-green-400 font-semibold uppercase tracking-widest mb-1">Admin</p>
                <h2 class="text-xl font-bold text-white">Event Calendar</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.summary-pdf') }}" class="btn-slate inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('dashboard') }}" class="btn-slate inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-5">
                <p class="text-xs text-slate-500 mb-4">คลิกอีเวนต์เพื่อดูรายละเอียด &bull; สีแสดงสถานะ: open / ongoing / closed / completed / cancelled</p>
                <div id="admin-calendar"></div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css" rel="stylesheet">
    <style>
        /* Dark FullCalendar overrides */
        .fc { color: #cbd5e1; }
        .fc .fc-toolbar-title { color: #f1f5f9; font-size: 1.1rem; font-weight: 700; }
        .fc .fc-button { background: rgba(255,255,255,0.06) !important; border-color: rgba(255,255,255,0.1) !important; color: #94a3b8 !important; font-size: 0.8rem !important; }
        .fc .fc-button:hover { background: rgba(255,255,255,0.12) !important; color: #e2e8f0 !important; }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active { background: rgba(16,185,129,0.15) !important; border-color: rgba(16,185,129,0.3) !important; color: #34d399 !important; }
        .fc th { background: rgba(255,255,255,0.03); color: #64748b; font-size: 0.75rem; border-color: rgba(255,255,255,0.05) !important; }
        .fc td { border-color: rgba(255,255,255,0.05) !important; }
        .fc .fc-daygrid-day { background: transparent; }
        .fc .fc-daygrid-day:hover { background: rgba(255,255,255,0.02); }
        .fc .fc-daygrid-day-number { color: #64748b; font-size: 0.8rem; }
        .fc .fc-daygrid-day.fc-day-today { background: rgba(16,185,129,0.06); }
        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number { color: #34d399; font-weight: 700; }
        .fc .fc-scrollgrid { border-color: rgba(255,255,255,0.05) !important; }
        .fc .fc-col-header-cell { border-color: rgba(255,255,255,0.05) !important; }
        .fc-event { font-size: 0.72rem !important; border-radius: 4px !important; padding: 1px 4px !important; border: none !important; }
        .fc-popover { background: #1e293b !important; border-color: rgba(255,255,255,0.1) !important; color: #e2e8f0; }
        .fc-popover-title { background: rgba(255,255,255,0.05) !important; color: #f1f5f9 !important; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('admin-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 750,
                locale: 'th',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($calendarEvents),
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                }
            });

            calendar.render();
        });
    </script>
</x-app-layout>
