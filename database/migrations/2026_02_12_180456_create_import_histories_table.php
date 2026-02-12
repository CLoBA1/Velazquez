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
        Schema::create('import_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_name');
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('created_count')->default(0);
            $table->integer('updated_count')->default(0); // Future proofing
            $table->integer('skipped_count')->default(0);
            $table->integer('error_count')->default(0);
            $table->json('summary_data')->nullable(); // Flexible field for extras
            $table->timestamps();
        });

        Schema::create('import_history_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_history_id')->constrained()->cascadeOnDelete();
            $table->integer('row_number');
            $table->string('status'); // success, skipped, error
            $table->text('message')->nullable();
            $table->json('row_data')->nullable(); // Store the original row data for debugging
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_history_details');
        Schema::dropIfExists('import_histories');
    }
};
