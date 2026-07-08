<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_tips', function (Blueprint $table) {
            $table->id();

            // Reporter Info (Optional for anonymity, flexible for non-students)
            $table->string('reporter_name')->nullable();
            $table->string('reporter_contact')->nullable(); // Can be grade/section OR a phone number

            // The Core Incident
            $table->string('incident_location');
            $table->text('narrative');

            // Evidence
            $table->string('evidence_path')->nullable();
            $table->string('video_link')->nullable();

            // Triage Status
            // unverified: New in queue
            // verified_student: Moved to official IncidentReport
            // turned_over_external: Sent to Barangay/Police (Vendor/Trespasser issues)
            // dismissed: Fake report / Spam
            $table->enum('status', [
                'unverified',
                'verified_student',
                'turned_over_external',
                'dismissed'
            ])->default('unverified');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_tips');
    }
};
