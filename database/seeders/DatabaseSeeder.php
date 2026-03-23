<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
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
        // สร้างสิทธิ์พื้นฐานของระบบก่อนเสมอ
        // เพื่อให้การ assign role ของผู้ใช้ในขั้นตอนถัดไปไม่ล้มเหลว
        $this->call(RoleSeeder::class);

        // -----------------------------------------------------------------
        // 1) สร้างบัญชีผู้ดูแลระบบ (Admin)
        // -----------------------------------------------------------------
        // ใช้ firstOrCreate เพื่อรองรับการรัน seeder ซ้ำโดยไม่สร้างข้อมูลซ้ำซ้อน
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'full_name' => 'ผู้ดูแลระบบหลัก',
                'phone' => '0900000000',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // -----------------------------------------------------------------
        // 2) สร้างผู้จัดงาน (Organizer)
        // -----------------------------------------------------------------
        // ข้อมูลกลุ่มนี้ทำให้หน้า dashboard/event list มีความสมจริงมากขึ้น
        $organizerProfiles = [
            ['name' => 'organizer_a', 'full_name' => 'ฝ่ายกิจกรรมสำนักวิทยบริการ', 'email' => 'organizer.a@example.com', 'phone' => '0810001001'],
            ['name' => 'organizer_b', 'full_name' => 'ชมรมพัฒนาซอฟต์แวร์', 'email' => 'organizer.b@example.com', 'phone' => '0810001002'],
            ['name' => 'organizer_c', 'full_name' => 'ศูนย์บ่มเพาะผู้ประกอบการ', 'email' => 'organizer.c@example.com', 'phone' => '0810001003'],
        ];

        $organizers = collect();
        foreach ($organizerProfiles as $profile) {
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

            $organizers->push($organizer);
        }

        // -----------------------------------------------------------------
        // 3) สร้างผู้เข้าร่วมงาน (Attendee)
        // -----------------------------------------------------------------
        // ทำเป็นช่วงข้อมูลที่คงที่ 15 คน เพื่อให้ทดสอบการลงทะเบียน/เช็คอินได้ต่อเนื่อง
        $attendees = collect();
        for ($index = 1; $index <= 15; $index++) {
            $attendee = User::firstOrCreate(
                ['email' => "attendee{$index}@example.com"],
                [
                    'name' => "attendee{$index}",
                    'full_name' => "ผู้เข้าร่วมทดสอบ {$index}",
                    'phone' => '08' . str_pad((string) (10001000 + $index), 8, '0', STR_PAD_LEFT),
                    'role' => 'user',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (! $attendee->hasRole('attendee')) {
                $attendee->assignRole('attendee');
            }

            $attendees->push($attendee);
        }

        // -----------------------------------------------------------------
        // 4) สร้างอีเวนต์จริงหลากหลายสถานะและช่วงเวลา
        // -----------------------------------------------------------------
        // ใช้ข้อมูลแบบใช้งานจริง (สัมมนา/เวิร์กช็อป/อบรม) เพื่อทดสอบทุกหน้าในระบบ
        // - open: เปิดลงทะเบียนได้
        // - closed: ปิดรับสมัครแล้ว แต่ยังไม่ใช่งานที่ยกเลิก
        // - ongoing: กำลังจัดงาน
        // - completed: จบงานแล้ว
        // - cancelled: ยกเลิก
        $eventTemplates = [
            [
                'title' => 'Workshop: Laravel สำหรับระบบงานจริง',
                'description' => 'เวิร์กช็อปเชิงปฏิบัติการสำหรับทีมพัฒนาที่ต้องการยกระดับโค้ดคุณภาพ',
                'location' => 'ห้องประชุม IT-401',
                'status' => 'open',
                'start_offset_hours' => 48,
                'duration_hours' => 4,
                'max_attendees' => 80,
            ],
            [
                'title' => 'เสวนา: แนวทางจัดการอีเวนต์ยุคดิจิทัล',
                'description' => 'เสวนาร่วมกับผู้จัดงานจริง เน้นกลยุทธ์การสื่อสารและการวัดผลผู้เข้าร่วม',
                'location' => 'Auditorium อาคาร A',
                'status' => 'open',
                'start_offset_hours' => 120,
                'duration_hours' => 3,
                'max_attendees' => 150,
            ],
            [
                'title' => 'อบรมความปลอดภัยข้อมูลสำหรับผู้จัดงาน',
                'description' => 'หลักสูตรสั้นสำหรับผู้ประสานงานและผู้ดูแลระบบหลังบ้าน',
                'location' => 'Training Room 2',
                'status' => 'closed',
                'start_offset_hours' => 24,
                'duration_hours' => 2,
                'max_attendees' => 40,
            ],
            [
                'title' => 'Open House ชมรมพัฒนาซอฟต์แวร์',
                'description' => 'กิจกรรมแนะนำชมรมและเส้นทางการเรียนรู้ด้านเว็บแอปและ DevOps',
                'location' => 'ลานกิจกรรมกลาง',
                'status' => 'ongoing',
                'start_offset_hours' => -1,
                'duration_hours' => 3,
                'max_attendees' => 120,
            ],
            [
                'title' => 'Hackathon เตรียมทีมแข่งขันระดับประเทศ',
                'description' => 'กิจกรรมระดมความคิดและสร้างต้นแบบภายในเวลา 1 วัน',
                'location' => 'Innovation Lab',
                'status' => 'completed',
                'start_offset_hours' => -72,
                'duration_hours' => 8,
                'max_attendees' => 60,
            ],
            [
                'title' => 'กิจกรรมพิเศษที่ยกเลิก',
                'description' => 'รายการนี้ใช้ทดสอบ workflow สถานะ cancelled และหน้ารายงาน',
                'location' => 'ห้องสัมมนา B-201',
                'status' => 'cancelled',
                'start_offset_hours' => 96,
                'duration_hours' => 2,
                'max_attendees' => 30,
            ],
        ];

        $events = collect();
        foreach ($eventTemplates as $index => $template) {
            $start = now()->copy()->addHours($template['start_offset_hours']);
            $end = $start->copy()->addHours($template['duration_hours']);

            $event = Event::create([
                'user_id' => $organizers[$index % $organizers->count()]->id,
                'title' => $template['title'],
                'description' => $template['description'],
                'location' => $template['location'],
                'start_datetime' => $start,
                'end_datetime' => $end,
                'max_participants' => $template['max_attendees'],
                'max_attendees' => $template['max_attendees'],
                'current_attendees' => 0,
                'status' => $template['status'],
            ]);

            $events->push($event);
        }

        // -----------------------------------------------------------------
        // 5) สร้างการลงทะเบียนแบบผสมหลายสถานะ
        // -----------------------------------------------------------------
        // รูปแบบด้านล่างช่วยให้ทดสอบรายงานและเงื่อนไขต่าง ๆ ได้ครบ
        // โดยเฉพาะ registered / checked_in / cancelled / no_show
        foreach ($events as $eventIndex => $event) {
            $sampleAttendees = $attendees->shuffle()->take(8);

            foreach ($sampleAttendees as $attendeeIndex => $attendee) {
                $status = match (true) {
                    $event->status === 'cancelled' => 'cancelled',
                    $event->status === 'completed' && $attendeeIndex < 3 => 'checked_in',
                    $event->status === 'completed' && $attendeeIndex >= 3 && $attendeeIndex < 5 => 'no_show',
                    $event->status === 'ongoing' && $attendeeIndex < 2 => 'checked_in',
                    $event->status === 'closed' && $attendeeIndex === 0 => 'cancelled',
                    default => 'registered',
                };

                Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $attendee->id,
                    'status' => $status,
                    'registered_at' => now()->subDays(rand(1, 20)),
                    'check_in_time' => $status === 'checked_in'
                        ? now()->subHours(rand(1, 24))
                        : null,
                ]);
            }

            // ซิงก์จำนวนผู้เข้าร่วมปัจจุบันให้ตรงกับข้อมูล registration
            // เงื่อนไขนับเฉพาะผู้ที่ยังถือว่าเข้าร่วมงานอยู่จริง
            $confirmedCount = Registration::query()
                ->where('event_id', $event->id)
                ->whereIn('status', ['registered', 'checked_in'])
                ->count();

            $event->update([
                'current_attendees' => $confirmedCount,
            ]);
        }

        // บัญชีตัวอย่างมาตรฐานเพิ่มเติมสำหรับล็อกอินทดสอบเร็ว
        $demoUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'user',
                'full_name' => 'ผู้ใช้งานตัวอย่าง',
                'phone' => '0911111111',
                'role' => 'user',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $demoUser->hasRole('attendee')) {
            $demoUser->assignRole('attendee');
        }
    }
}
