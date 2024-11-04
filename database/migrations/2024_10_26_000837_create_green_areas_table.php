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
        Schema::create('green_areas', function (Blueprint $table) {
            $table->id(); // Otomatik artan ID kolonu
            $table->string('name'); // Park alanı adı
            $table->text('description')->nullable(); // Açıklama
            $table->decimal('latitude', 10, 6); // Enlem (latitude)
            $table->decimal('longitude', 10, 6); // Boylam (longitude)
            $table->timestamps(); // Oluşturulma ve güncelleme zaman damgaları
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('green_areas');
    }
};
