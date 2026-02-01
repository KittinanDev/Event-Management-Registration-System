<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get all events and their registrations
$events = \App\Models\Event::all();
echo "=== All Events ===\n";
foreach ($events as $event) {
    echo "\nEvent {$event->id}: {$event->title}\n";
    echo "  Start: {$event->start_datetime}\n";
    echo "  End: {$event->end_datetime}\n";
    echo "  Current time: " . now() . "\n";
    
    $now = now();
    $inWindow = $now >= $event->start_datetime && $now <= $event->end_datetime;
    echo "  In check-in window? " . ($inWindow ? 'YES ✓' : 'NO ✗') . "\n";
    
    // Show registrations
    $registrations = $event->registrations;
    echo "  Registrations:\n";
    foreach ($registrations as $reg) {
        echo "    - User {$reg->user_id}: Status = {$reg->status}\n";
    }
}
