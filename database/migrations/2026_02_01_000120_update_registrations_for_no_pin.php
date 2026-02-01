<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->timestamp('check_in_time')->nullable()->after('registered_at');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE registrations MODIFY status ENUM('registered','checked_in','cancelled','no_show') DEFAULT 'registered'");
        }

        DB::table('registrations')->update([
            'status' => DB::raw("CASE status WHEN 'confirmed' THEN 'registered' WHEN 'cancelled' THEN 'cancelled' ELSE status END")
        ]);
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE registrations MODIFY status ENUM('confirmed','cancelled') DEFAULT 'confirmed'");
        }

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('check_in_time');
        });

        DB::table('registrations')->update([
            'status' => DB::raw("CASE status WHEN 'registered' THEN 'confirmed' WHEN 'cancelled' THEN 'cancelled' ELSE status END")
        ]);
    }
};
