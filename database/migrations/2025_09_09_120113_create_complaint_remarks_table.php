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
        Schema::create('complaint_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->string('photo_path')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'cancelled'])->nullable();
            $table->json('additional_data')->nullable(); // For storing any extra data like part details
            $table->timestamps();
            
            // Indexes
            $table->index(['complaint_id', 'staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint_remarks');
    }
};
