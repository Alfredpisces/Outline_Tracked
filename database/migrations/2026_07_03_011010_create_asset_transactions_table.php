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
    Schema::create('asset_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Teacher logging the action

        $table->enum('action_type', ['Borrowed', 'Consumed', 'Returned', 'Condition Change', 'Added Stock']);
        $table->integer('quantity'); // How many items were involved in this action

        // Text-based borrower info (protects system scope from needing full student DB)
        $table->string('borrower_name')->nullable();
        $table->enum('borrower_type', ['Student', 'Teacher', 'Staff'])->nullable();

        // Timestamps for Borrowing
        $table->timestamp('borrowed_at')->nullable();
        $table->date('expected_return_date')->nullable();
        $table->timestamp('returned_at')->nullable();

        $table->enum('status', ['Active', 'Completed', 'Overdue', 'N/A'])->default('N/A');

        $table->text('notes')->nullable();
        $table->string('photo_path')->nullable(); // Evidence of damage upon return

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transactions');
    }
};
