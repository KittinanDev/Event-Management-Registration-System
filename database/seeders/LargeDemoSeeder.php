<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class LargeDemoSeeder extends Seeder
{
    /**
     * Seed a large amount of demo data to make dashboards and lists look populated.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $organizers = User::query()->whereIn('email', [
            'organizer.a@example.com',
            'organizer.b@example.com',
            'organizer.c@example.com',
        ])->get();

        if ($organizers->count() < 3) {
            $profiles = [
                ['name' => 'organizer_a', 'full_name' => 'ฝ่ายกิจกรรมสำนักวิทยบริการ', 'email' => 'organizer.a@example.com', 'phone' => '0810001001'],
                ['name' => 'organizer_b', 'full_name' => 'ชมรมพัฒนาซอฟต์แวร์', 'email' => 'organizer.b@example.com', 'phone' => '0810001002'],
                ['name' => 'organizer_c', 'full_name' => 'ศูนย์บ่มเพาะผู้ประกอบการ', 'email' => 'organizer.c@example.com', 'phone' => '0810001003'],
            ];

            foreach ($profiles as $profile) {
                $organizer = User::firstOrCreate(
                    ['email' => $profile['email']],
                    [
                        'name' => $profile['name'],
                        'full_name' => $profile['full_name'],
                        'phone' => $profile['phone'],
                        'role' => 'user',
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );

                if (! $organizer->hasRole('organizer')) {
                    $organizer->assignRole('organizer');
                }
            }

            $organizers = User::query()->whereIn('email', [
                'organizer.a@example.com',
                'organizer.b@example.com',
                'organizer.c@example.com',
            ])->get();
        }

        // Ensure enough attendees for realistic registration volumes.
        $targetAttendeeCount = 180;
        for ($i = 1; $i <= $targetAttendeeCount; $i++) {
            $email = sprintf('attendee%03d@example.com', $i);

            $attendee = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'attendee' . $i,
                    'full_name' => 'ผู้เข้าร่วมเดโม ' . $i,
                    'phone' => '08' . str_pad((string) (20000000 + $i), 8, '0', STR_PAD_LEFT),
                    'role' => 'user',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (! $attendee->hasRole('attendee')) {
                $attendee->assignRole('attendee');
            }
        }

        $attendees = User::query()->where('email', 'like', 'attendee%@example.com')->get();

        $topics = [
            'Laravel', 'AI Product', 'Data Analytics', 'UX Design', 'Cloud Native',
            'Cyber Security', 'Startup Pitch', 'DevOps', 'Product Management', 'Mobile App',
        ];

        $formats = ['Workshop', 'Seminar', 'Bootcamp', 'Meetup', 'Talk'];
        $locations = [
            'Auditorium อาคาร A', 'Innovation Lab', 'Training Room 2', 'ห้องประชุม IT-401',
            'Co-working Space ชั้น 3', 'Main Hall', 'ศูนย์การเรียนรู้ดิจิทัล',
        ];

        $eventCount = 60;

        for ($index = 1; $index <= $eventCount; $index++) {
            $status = match (true) {
                $index <= 40 => 'open',
                $index <= 46 => 'ongoing',
                $index <= 52 => 'closed',
                $index <= 57 => 'completed',
                default => 'cancelled',
            };

            $topic = $topics[array_rand($topics)];
            $format = $formats[array_rand($formats)];
            $location = $locations[array_rand($locations)];
            $capacity = rand(50, 250);

            $start = match ($status) {
                'open' => Carbon::now()->addDays(rand(2, 60))->setTime(rand(8, 18), [0, 30][array_rand([0, 30])]),
                'ongoing' => Carbon::now()->subHours(rand(1, 3)),
                'closed' => Carbon::now()->addDays(rand(1, 20))->setTime(rand(9, 17), 0),
                'completed' => Carbon::now()->subDays(rand(2, 45))->setTime(rand(9, 17), 0),
                default => Carbon::now()->addDays(rand(3, 45))->setTime(rand(9, 17), 0),
            };

            $end = (clone $start)->addHours(rand(2, 8));

            $event = Event::create([
                'user_id' => $organizers[$index % $organizers->count()]->id,
                'title' => sprintf('%s: %s Session #%02d', $format, $topic, $index),
                'description' => sprintf('กิจกรรมเดโมสำหรับหัวข้อ %s เพื่อใช้ทดสอบระบบลงทะเบียน เช็กอิน และรายงาน (ชุดใหญ่ลำดับ %02d)', $topic, $index),
                'location' => $location,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'max_participants' => $capacity,
                'max_attendees' => $capacity,
                'current_attendees' => 0,
                'status' => $status,
            ]);

            $registrationTarget = min(rand(20, 90), $capacity);
            $sampleAttendees = $attendees->shuffle()->take($registrationTarget);

            foreach ($sampleAttendees as $attendeeIndex => $attendee) {
                $registrationStatus = match ($status) {
                    'open' => 'registered',
                    'ongoing' => $attendeeIndex < (int) floor($registrationTarget * 0.35) ? 'checked_in' : 'registered',
                    'closed' => $attendeeIndex < (int) floor($registrationTarget * 0.85) ? 'registered' : 'cancelled',
                    'completed' => $attendeeIndex < (int) floor($registrationTarget * 0.55)
                        ? 'checked_in'
                        : ($attendeeIndex < (int) floor($registrationTarget * 0.85) ? 'no_show' : 'cancelled'),
                    default => 'cancelled',
                };

                Registration::firstOrCreate(
                    [
                        'event_id' => $event->id,
                        'user_id' => $attendee->id,
                    ],
                    [
                        'status' => $registrationStatus,
                        'registered_at' => Carbon::now()->subDays(rand(1, 60)),
                        'check_in_time' => $registrationStatus === 'checked_in'
                            ? Carbon::now()->subHours(rand(1, 36))
                            : null,
                    ]
                );
            }

            $confirmedCount = Registration::query()
                ->where('event_id', $event->id)
                ->whereIn('status', ['registered', 'checked_in'])
                ->count();

            $event->update(['current_attendees' => $confirmedCount]);
        }
    }
}
