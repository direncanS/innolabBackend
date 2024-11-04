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
        Schema::create('transit_stops', function (Blueprint $table) {
            $table->id(); // Otomatik artan ID kolonu
            $table->string('name'); // Durak adı
            $table->string('address')->nullable(); // Adres (opsiyonel olabilir)
            $table->decimal('latitude', 10, 6); // Enlem (latitude) - ondalık basamakları ayarlanmış
            $table->decimal('longitude', 10, 6); // Boylam (longitude) - ondalık basamakları ayarlanmış
            $table->timestamps(); // Oluşturulma ve güncelleme zaman damgaları
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transit_stops');
    }
};


