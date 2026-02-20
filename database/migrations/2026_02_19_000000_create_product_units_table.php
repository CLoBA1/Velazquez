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
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

            // Factor de conversión: cuánto de la unidad base representa esta unidad.
            // Ej: Si base es Metro y esta unidad es Rollo (100m), factor = 100.
            $table->decimal('conversion_factor', 10, 4)->default(1);

            // Precios específicos para esta presentación (opcionales, si null se calcula)
            // En este MVP los haremos explícitos para simplificar.
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('public_price', 12, 2)->nullable();
            $table->decimal('mid_wholesale_price', 12, 2)->nullable();
            $table->decimal('wholesale_price', 12, 2)->nullable();

            $table->string('barcode')->nullable();

            $table->timestamps();

            // Un producto no debería tener duplicada la misma unidad extra (aunque podría debatirse, por ahora unique)
            $table->unique(['product_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_units');
    }
};
