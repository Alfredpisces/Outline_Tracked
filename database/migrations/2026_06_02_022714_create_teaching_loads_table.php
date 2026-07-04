<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->string('grade_level');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_loads');
    }
};
