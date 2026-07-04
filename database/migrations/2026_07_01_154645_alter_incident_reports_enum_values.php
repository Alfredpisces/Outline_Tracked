<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, the safest way to modify an ENUM is using a raw DB statement.
        // We are adding 'critical' to severity.
        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN severity ENUM('minor', 'major', 'critical') NOT NULL");

        // We are adding 'turned_over' and 'overridden' to status.
        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN status ENUM('pending', 'in_intervention', 'resolved', 'escalated', 'turned_over', 'overridden') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original allowed values if we ever roll back.
        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN severity ENUM('minor', 'major') NOT NULL");

        DB::statement("ALTER TABLE incident_reports MODIFY COLUMN status ENUM('pending', 'in_intervention', 'resolved', 'escalated') NOT NULL DEFAULT 'pending'");
    }
};
