<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
        });

        // Backfill full_name from name for existing users
        DB::table('users')->whereNull('full_name')->update([
            'full_name' => DB::raw('name')
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone', 'role']);
        });
    }
};
