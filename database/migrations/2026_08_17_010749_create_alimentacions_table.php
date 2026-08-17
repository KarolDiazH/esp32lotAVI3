<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('alimentacions', function (Blueprint $table) {
        $table->id();
        $table->string('device_id');
        $table->float('peso_comida')->default(2000); // Iniciamos con 2000g (2kg)
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alimentacions');
    }
};
