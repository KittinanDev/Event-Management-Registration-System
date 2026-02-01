<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'full_name' => 'Admin User',
                'phone' => '0900000000',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'full_name' => 'Regular User',
                'phone' => '0911111111',
                'role' => 'user',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('attendee')) {
            $user->assignRole('attendee');
        }

        if (Event::count() === 0) {
            $now = now();
            $statuses = ['open', 'closed', 'ongoing', 'completed', 'cancelled'];

            for ($i = 1; $i <= 10; $i++) {
                $status = $statuses[($i - 1) % count($statuses)];

                $startDateTime = $now->copy()->addHours($i)->setMinute(0)->setSecond(0);
                $endDateTime = $startDateTime->copy()->addHours(2);

                if ($status === 'ongoing') {
                    $startDateTime = $now->copy()->subHours(1);
                    $endDateTime = $now->copy()->addHours(1);
                } elseif ($status === 'completed' || $status === 'closed') {
                    $startDateTime = $now->copy()->subHours($i + 5);
                    $endDateTime = $startDateTime->copy()->addHours(2);
                }

                Event::create([
                    'user_id' => $admin->id,
                    'title' => "Event #{$i} ({$status})",
                    'description' => "ตัวอย่างอีเวนต์สถานะ {$status}",
                    'location' => "Venue {$i}",
                    'start_datetime' => $startDateTime,
                    'end_datetime' => $endDateTime,
                    'max_attendees' => 50,
                    'current_attendees' => 0,
                    'status' => $status,
                ]);
            }
        }
    }
}
