<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lis_stagings', function (Blueprint $table) {
            $table->id();

            // Exactly matching the DepEd Excel Headers
            $table->string('lrn')->index();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable(); // Added from image
            $table->string('gender')->nullable(); // Added from image
            $table->string('birthdate')->nullable(); // Added from image
            $table->string('grade_level');
            $table->string('section');
            $table->string('curriculum')->nullable(); // Added from image
            $table->string('status')->nullable();

            // Our injected tracking column
            $table->string('school_year')->index();

            $table->timestamps();

            // Prevent duplicate LRNs in the same school year
            $table->unique(['lrn', 'school_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lis_stagings');
    }
};
