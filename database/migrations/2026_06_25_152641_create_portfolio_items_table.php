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
        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            // Link to the teacher who owns this record
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Core Achievement Data
            $table->string('title');
            $table->string('category'); // e.g., 'Seminars & Training', 'Education', 'Awards'
            $table->string('level')->nullable(); // e.g., 'School', 'District', 'National'
            $table->date('date_earned');
            $table->text('description')->nullable();
            
            // File attachment for the Certificate/Proof
            $table->string('file_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
    }
};