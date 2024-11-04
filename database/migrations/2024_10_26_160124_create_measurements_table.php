<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained('stations')->onDelete('cascade'); // İstasyon ID'si
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade'); // Komponent ID'si
            $table->timestamp('measurement_time'); // Ölçüm zamanı
            $table->decimal('value', 8, 2)->nullable(); // Ölçüm değeri
            $table->string('unit'); // Ölçüm birimi (örneğin µg/m³)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
}

