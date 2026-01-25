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
    Schema::create('products', function (Blueprint $table) {
        $table->id();

        // Identificadores
        $table->string('internal_code')->unique(); // tu código interno
        $table->string('supplier_sku')->nullable()->index();
        $table->string('barcode')->nullable()->index();

        // Relación catálogo
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
        $table->foreignId('unit_id')->constrained()->cascadeOnDelete();

        // Datos
        $table->string('name');
        $table->text('description'); // obligatorio
        $table->string('slug')->unique();

        // Foto principal (ruta relativa)
        $table->string('main_image_path')->nullable();

        // Precios (obligatorios)
        $table->decimal('cost_price', 12, 2);
        $table->decimal('sale_price', 12, 2);
        $table->decimal('public_price', 12, 2);
        $table->decimal('mid_wholesale_price', 12, 2);
        $table->decimal('wholesale_price', 12, 2);

        $table->timestamps();

        $table->index(['category_id', 'brand_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
