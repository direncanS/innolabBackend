<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationDataTable extends Migration
{
    public function up(): void
    {
        Schema::create('education_data', function (Blueprint $table) {
            $table->id();
            $table->string('nuts');                     // NUTS kodu
            $table->string('district_code');            // Bölge kodu
            $table->string('sub_district_code');        // Alt bölge kodu
            $table->integer('ref_year');                // Referans yılı
            $table->string('ref_date')->nullable();     // Referans tarihi
            $table->float('edu_all')->nullable();       // Tüm eğitim seviyeleri
            $table->float('edu_leh')->nullable();       // Lehrabschlüsse
            $table->float('edu_bms')->nullable();       // Berufsbildende mittlere Schule
            $table->float('edu_ahs')->nullable();       // Allgemeinbildende höhere Schule
            $table->float('edu_bhs')->nullable();       // Berufsbildende höhere Schule
            $table->float('edu_kol')->nullable();       // Kollegs
            $table->float('edu_aca')->nullable();       // Akademie
            $table->float('edu_uni')->nullable();       // Üniversite
            $table->float('edu_aka')->nullable();       // Akademische Ausbildung
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education_data');
    }
}
