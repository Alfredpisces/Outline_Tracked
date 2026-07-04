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
        Schema::table('portfolio_items', function (Blueprint $table) {
            // We use this check to prevent errors just in case a column somehow exists
            if (!Schema::hasColumn('portfolio_items', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
                $table->string('title')->after('user_id');
                $table->string('category')->after('title');
                $table->string('level')->nullable()->after('category');
                $table->date('date_earned')->after('level');
                $table->text('description')->nullable()->after('date_earned');
                $table->string('file_path')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'title', 'category', 'level', 'date_earned', 'description', 'file_path'
            ]);
        });
    }
};
