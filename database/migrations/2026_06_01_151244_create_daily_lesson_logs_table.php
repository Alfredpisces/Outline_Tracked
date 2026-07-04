<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add this line to drop the old 4A's table first!
        Schema::dropIfExists('daily_lesson_logs');
        Schema::create('teaching_loads', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
        $table->string('subject');
        $table->string('grade_level');
        $table->timestamps();
    });

        Schema::create('daily_lesson_logs', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();

            // Header Info
            $table->string('subject');
            $table->string('grade_quarter');
            $table->string('teaching_dates');

            // I & II. Standards and Resources
            $table->text('content_standard');
            $table->text('competencies');
            $table->text('resources');

            // III. Procedures (MATATAG)
            $table->text('procedure_a');
            $table->text('procedure_b');
            $table->text('procedure_c');
            $table->text('procedure_d');
            $table->text('procedure_e');

            // IV. Reflection
            $table->integer('passed_count')->nullable();
            $table->integer('remedial_count')->nullable();
            $table->text('reflection_notes')->nullable();

            // Administrative
            $table->text('principal_feedback')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_lesson_logs');
    }
};
