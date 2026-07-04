<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cot_observations', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('observer_id')->nullable()->constrained('users')->nullOnDelete();

            // Workflow Status: 'pending', 'scheduled', 'completed'
            $table->string('status')->default('pending');

            // Scheduling Details
            $table->date('observation_date');
            $table->time('time_started')->nullable();
            $table->time('time_ended')->nullable();

            // Class Metadata
            $table->string('subject_taught');
            $table->string('grade_level');
            $table->string('section');
            $table->integer('quarter');

            // Final Evaluation Data
            $table->decimal('rating', 4, 2)->nullable();
            $table->text('comments')->nullable();
            $table->json('scores')->nullable(); // Stores the 1-9 indicators

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cot_observations');
    }
};
