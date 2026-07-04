<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dll_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('daily_lesson_log_id')
                ->constrained('daily_lesson_logs')
                ->cascadeOnDelete();

            // One row per day in the week
            $table->date('entry_date')->index();

            // Daily content fields (you can expand later)
            $table->text('procedure_a')->nullable();
            $table->text('procedure_b')->nullable();
            $table->text('procedure_c')->nullable();
            $table->text('procedure_d')->nullable();
            $table->text('procedure_e')->nullable();

            // Optional: per-day resources/reflection
            $table->text('resources')->nullable();
            $table->integer('passed_count')->nullable();
            $table->integer('remedial_count')->nullable();
            $table->text('reflection_notes')->nullable();

            $table->timestamps();

            // Prevent duplicates for same week/day
            $table->unique(['daily_lesson_log_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dll_entries');
    }
};
