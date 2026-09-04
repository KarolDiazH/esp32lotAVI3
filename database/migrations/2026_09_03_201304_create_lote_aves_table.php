<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lote_aves', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_lote')->unique(); // Ej: LOTE-01
            $table->string('raza')->default('Codorniz Japónica'); // Ej: Coturnix Japónica
            $table->integer('cantidad_inicial'); // Ej: 150
            $table->integer('cantidad_actual');  // Ej: 148 (por si hubo bajas)
            $table->date('fecha_ingreso');
            $table->integer('edad_semanas')->default(6);
            $table->enum('estado', ['En Crecimiento', 'En Postura', 'Finalizado'])->default('En Postura');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote_aves');
    }
};