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
        // Drop existing foreign key constraints if they exist
        Schema::table('part_entries', function (Blueprint $table) {
            // This will remove the foreign key constraint if it exists
            $table->dropForeign(['item_id']);
            $table->dropForeign(['user_id']);
        });

        // Recreate the foreign key constraints with proper onDelete behavior
        Schema::table('part_entries', function (Blueprint $table) {
            // Add foreign key to items table
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
                  ->onDelete('cascade');
            
            // Add foreign key to users table
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraints
        Schema::table('part_entries', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['user_id']);
        });

        // Recreate the original foreign key constraints
        Schema::table('part_entries', function (Blueprint $table) {
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
                  ->onDelete('cascade');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
