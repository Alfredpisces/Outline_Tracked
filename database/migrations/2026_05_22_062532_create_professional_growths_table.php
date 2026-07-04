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
    Schema::create('professional_growths', function (Blueprint $table) {
        $table->id();
        $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
        $table->string('title');
        $table->timestamp('date_attended')->nullable();
        $table->integer('points')->default(0);
        $table->string('verification_status')->default('pending');
        $table->foreignId('verified_by')->nullable()->constrained('users');
        $table->timestamp('verified_at')->nullable();
        $table->timestamps();
    });
}};
