<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$event = \App\Models\Event::first();
echo "=== Event Times ===\n";
echo "start_datetime: " . $event->start_datetime . "\n";
echo "end_datetime: " . $event->end_datetime . "\n";
echo "\n=== Current Time ===\n";
echo "now(): " . now() . "\n";
echo "\n=== Timezone ===\n";
echo "Config timezone: " . config('app.timezone') . "\n";
echo "PHP timezone: " . date_default_timezone_get() . "\n";
echo "\n=== Time Comparison ===\n";
$now = now();
echo "now() >= start? " . ($now >= $event->start_datetime ? 'true' : 'false') . "\n";
echo "now() <= end? " . ($now <= $event->end_datetime ? 'true' : 'false') . "\n";
echo "Is within window? " . ($now >= $event->start_datetime && $now <= $event->end_datetime ? 'YES' : 'NO') . "\n";
