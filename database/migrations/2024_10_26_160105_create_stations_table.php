<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // İstasyon kodu (örneğin S415)
            $table->string('short_name'); // İstasyon kısa adı (örneğin Linz-24er-Turm)
            $table->string('long_name'); // İstasyon tam adı
            $table->decimal('latitude', 10, 6); // Enlem (Latitude)
            $table->decimal('longitude', 10, 6); // Boylam (Longitude)
            $table->json('component_codes'); // İstasyonun ölçtüğü komponent kodları
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
}
