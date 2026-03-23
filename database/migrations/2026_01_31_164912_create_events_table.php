<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('location', 200);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->unsignedInteger('max_participants')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'open', 'closed', 'ongoing', 'completed'])->default('draft');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
