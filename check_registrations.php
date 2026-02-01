<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check all registrations
$registrations = \App\Models\Registration::all();
echo "=== All Registrations ===\n";
foreach ($registrations as $reg) {
    echo "Reg {$reg->id}: User {$reg->user_id}, Event {$reg->event_id}, Status: {$reg->status}, Check-in time: " . ($reg->check_in_time ? $reg->check_in_time : 'NULL') . "\n";
}
