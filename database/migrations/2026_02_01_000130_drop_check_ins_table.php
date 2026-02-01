<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('check_ins');
    }

    public function down(): void
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->timestamp('checked_in_at')->useCurrent();
            $table->foreignId('checked_in_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }
};
