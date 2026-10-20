<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hidratacions', function (Blueprint $table) {
            $table->boolean('flotador_bajo')->nullable()->after('nivel_agua');
            $table->boolean('flotador_alto')->nullable()->after('flotador_bajo');
            $table->boolean('bomba_encendida')->nullable()->after('flotador_alto');
        });
    }

    public function down(): void
    {
        Schema::table('hidratacions', function (Blueprint $table) {
            $table->dropColumn(['flotador_bajo', 'flotador_alto', 'bomba_encendida']);
        });
    }
};