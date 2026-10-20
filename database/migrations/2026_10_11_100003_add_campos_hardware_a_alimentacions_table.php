<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alimentacions', function (Blueprint $table) {
            $table->boolean('esta_dispensando')->nullable()->after('peso_comida');
            $table->unsignedInteger('contador_diario')->nullable()->after('esta_dispensando');
        });
    }

    public function down(): void
    {
        Schema::table('alimentacions', function (Blueprint $table) {
            $table->dropColumn(['esta_dispensando', 'contador_diario']);
        });
    }
};