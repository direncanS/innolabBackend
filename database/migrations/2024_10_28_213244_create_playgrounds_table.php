<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaygroundsTable extends Migration
{
    public function up(): void
    {
        Schema::create('playgrounds', function (Blueprint $table) {
            $table->id();
            $table->string('fid')->nullable();
            $table->string('objectid')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('name')->nullable();
            $table->integer('district_code')->nullable();
            $table->text('playground_detail')->nullable();
            $table->string('type_detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playgrounds');
    }
}
