<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Clear all events
\App\Models\Event::query()->delete();
echo "✓ Cleared all events\n\n";

// Get admin user
$admin = \App\Models\User::where('role', 'admin')->first();
if (!$admin) {
    echo "✗ No admin user found!\n";
    exit(1);
}

$now = now();
$statuses = ['open', 'closed', 'ongoing', 'completed', 'cancelled'];
$statusIndex = 0;

// Create 10 events with different statuses
for ($i = 1; $i <= 10; $i++) {
    $status = $statuses[$statusIndex % count($statuses)];
    $statusIndex++;
    
    // Set different time windows based on status
    $startDateTime = $now->copy()->addHours($i)->setMinute(0)->setSecond(0);
    $endDateTime = $startDateTime->copy()->addHours(2);
    
    if ($status === 'ongoing') {
        $startDateTime = $now->copy()->subHours(1);
        $endDateTime = $now->copy()->addHours(1);
    } elseif ($status === 'completed' || $status === 'closed') {
        $startDateTime = $now->copy()->subHours($i + 5);
        $endDateTime = $startDateTime->copy()->addHours(2);
    } elseif ($status === 'cancelled') {
        $startDateTime = $now->copy()->addHours($i);
        $endDateTime = $startDateTime->copy()->addHours(2);
    }
    
    $event = \App\Models\Event::create([
        'user_id' => $admin->id,
        'title' => "Event #$i ({$status})",
        'description' => "Test event with status: {$status}",
        'location' => "Venue $i",
        'start_datetime' => $startDateTime,
        'end_datetime' => $endDateTime,
        'max_attendees' => 50,
        'current_attendees' => 0,
        'status' => $status,
    ]);
    
    echo "✓ Created Event #{$i}: '{$event->title}' (Status: {$status})\n";
    echo "  - Start: {$startDateTime}\n";
    echo "  - End: {$endDateTime}\n";
}

echo "\n✓ Successfully created 10 events with different statuses\n";
