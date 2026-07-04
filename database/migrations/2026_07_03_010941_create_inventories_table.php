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
    Schema::create('inventories', function (Blueprint $table) {
        $table->id();
        $table->string('property_number')->unique()->nullable();
        $table->string('item_name');
        $table->string('category'); // e.g., IT Equipment, Office Supplies, TLE Tools

        // Lifespan & Condition
        $table->enum('lifespan_type', ['Consumable', 'Semi-Expendable', 'Non-Expendable']);
        $table->enum('condition', ['Serviceable', 'Needs Minor Repair', 'Needs Major Repair', 'Unserviceable', 'Missing'])->default('Serviceable');

        // Quantities & Alerts
        $table->integer('quantity')->default(1);
        $table->string('unit')->default('Pieces'); // e.g., Reams, Boxes, Sets
        $table->integer('reorder_level')->nullable();

        // Assignments & Location
        $table->enum('assignment_type', ['Personal', 'Facility'])->default('Facility');
        $table->string('location')->nullable(); // e.g., TLE Lab, Stockroom
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // The Custodian/Coordinator
        // Admin & Budget Data
        $table->enum('funding_source', ['MOOE', 'SEF / LGU', 'PTA', 'Donation', 'National / DepEd Central'])->nullable();
        $table->date('date_acquired')->nullable();
        $table->date('expiry_date')->nullable(); // Add this line for the new alerts
        $table->string('photo_path')->nullable();

        // Admin & Budget Data
        $table->enum('funding_source', ['MOOE', 'SEF / LGU', 'PTA', 'Donation', 'National / DepEd Central'])->nullable();
        $table->date('date_acquired')->nullable();
        $table->string('photo_path')->nullable(); // For damage evidence

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
