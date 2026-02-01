<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('max_attendees')->nullable()->after('end_datetime');
            $table->unsignedInteger('current_attendees')->default(0)->after('max_attendees');
        });

        // Backfill max_attendees from legacy max_participants
        DB::table('events')->whereNull('max_attendees')->update([
            'max_attendees' => DB::raw('max_participants')
        ]);

        // Update status values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY status ENUM('open','closed','ongoing','completed','cancelled') DEFAULT 'open'");
        }

        DB::table('events')->update([
            'status' => DB::raw("CASE status WHEN 'published' THEN 'open' WHEN 'draft' THEN 'closed' WHEN 'cancelled' THEN 'cancelled' ELSE status END")
        ]);

        // Initialize current_attendees from registrations
        foreach (DB::table('events')->get() as $event) {
            $count = DB::table('registrations')
                ->where('event_id', $event->id)
                ->whereIn('status', ['registered', 'checked_in'])
                ->count();
            DB::table('events')->where('id', $event->id)->update(['current_attendees' => $count]);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE events MODIFY status ENUM('draft','published','cancelled') DEFAULT 'draft'");
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['max_attendees', 'current_attendees']);
        });

        DB::table('events')->update([
            'status' => DB::raw("CASE status WHEN 'open' THEN 'published' WHEN 'closed' THEN 'draft' WHEN 'cancelled' THEN 'cancelled' ELSE status END")
        ]);
    }
};
