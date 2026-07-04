<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('daily_lesson_logs', function (Blueprint $table) {
            $table->date('week_start_date')->nullable()->index()->after('teaching_dates');
            $table->date('week_end_date')->nullable()->index()->after('week_start_date');

            // Helpful for rules (e.g., "deadline next Monday")
            $table->timestamp('due_at')->nullable()->after('week_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('daily_lesson_logs', function (Blueprint $table) {
            $table->dropColumn(['week_start_date', 'week_end_date', 'due_at']);
        });
    }
};
