<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // Título principal del slide
            $table->string('subtitle')->nullable(); // Texto pequeño arriba (badge)
            $table->text('description')->nullable(); // Texto descriptivo
            $table->string('image_path')->nullable(); // Ruta de imagen en storage
            $table->string('link_primary')->nullable(); // URL botón principal
            $table->string('label_primary')->default('Ver más'); // Texto botón principal
            $table->string('link_secondary')->nullable(); // URL botón secundario
            $table->string('label_secondary')->default('Ver Catálogo'); // Texto botón secundario
            $table->integer('sort_order')->default(0); // Orden de aparición
            $table->boolean('is_active')->default(true); // Visible o no
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
