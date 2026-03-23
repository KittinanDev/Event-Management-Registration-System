<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Summary Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; }
        h1 { margin: 0 0 6px 0; font-size: 22px; }
        h2 { margin: 20px 0 10px 0; font-size: 16px; }
        .muted { color: #6b7280; margin-bottom: 14px; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .summary td { border: 1px solid #d1d5db; padding: 10px; }
        .label { font-weight: bold; width: 45%; background: #f3f4f6; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        .table th { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>Event Management - Admin Summary</h1>
    <div class="muted">Generated at: {{ $generatedAt->format('d/m/Y H:i:s') }}</div>

    <table class="summary">
        <tr>
            <td class="label">Total Events</td>
            <td>{{ $eventCount }}</td>
        </tr>
        <tr>
            <td class="label">Total Registrations</td>
            <td>{{ $registrationCount }}</td>
        </tr>
        <tr>
            <td class="label">Checked In</td>
            <td>{{ $checkedInCount }}</td>
        </tr>
        <tr>
            <td class="label">Cancelled</td>
            <td>{{ $cancelledCount }}</td>
        </tr>
        <tr>
            <td class="label">No Show</td>
            <td>{{ $noShowCount }}</td>
        </tr>
    </table>

    <h2>Events by Status</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach(['open', 'ongoing', 'closed', 'completed', 'cancelled'] as $status)
                <tr>
                    <td>{{ ucfirst($status) }}</td>
                    <td>{{ $statusSummary[$status] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
