<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('social_markets', function (Blueprint $table) {
            $table->id();
            $table->string('shape');
            $table->string('name');
            $table->string('address');
            $table->integer('district_code');
            $table->string('langtext');
            $table->string('web_link');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_markets');
    }
};
