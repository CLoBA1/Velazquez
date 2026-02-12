<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tombstone: This migration is intentionally empty to bypass 
        // "Table already exists" errors. The table exists, we just need
        // the migration to be marked as "ran" in the database.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if it exists, though usually we might not want to drop it at all
        // if it's a system table.
    }
};
