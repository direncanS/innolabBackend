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
        Schema::create('kindergartens', function (Blueprint $table) {
            $table->id(); // Otomatik artan ID kolonu
            $table->string('name'); // Anaokulu adı
            $table->string('address'); // Adres
            $table->string('operator'); // İşletici
            $table->string('type'); // Anaokulu tipi
            $table->decimal('latitude'); // Enlem (latitude)
            $table->decimal('longitude'); // Boylam (longitude)
            $table->timestamps(); // Oluşturulma ve güncelleme zaman damgaları
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kindergartens');
    }
};
