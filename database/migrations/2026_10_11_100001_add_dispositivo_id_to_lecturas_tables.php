<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temperatura_humedads', function (Blueprint $table) {
            $table->foreignId('dispositivo_id')->nullable()->after('id')
                ->constrained('dispositivos')->nullOnDelete();
        });

        Schema::table('conteo_huevos', function (Blueprint $table) {
            $table->foreignId('dispositivo_id')->nullable()->after('id')
                ->constrained('dispositivos')->nullOnDelete();
        });

        Schema::table('alimentacions', function (Blueprint $table) {
            $table->foreignId('dispositivo_id')->nullable()->after('id')
                ->constrained('dispositivos')->nullOnDelete();
        });

        Schema::table('hidratacions', function (Blueprint $table) {
            $table->foreignId('dispositivo_id')->nullable()->after('id')
                ->constrained('dispositivos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        foreach (['temperatura_humedads', 'conteo_huevos', 'alimentacions', 'hidratacions'] as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->dropConstrainedForeignId('dispositivo_id');
            });
        }
    }
};