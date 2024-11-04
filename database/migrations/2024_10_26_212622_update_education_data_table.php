<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEducationDataTable extends Migration
{
    public function up(): void
    {
        Schema::table('education_data', function (Blueprint $table) {
            $table->date('ref_date')->change();
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('sub_district_id')->nullable()->constrained('subdistricts')->onDelete('set null');
            $table->dropColumn('district_code');
            $table->dropColumn('sub_district_code');
        });
    }

    public function down(): void
    {
        Schema::table('education_data', function (Blueprint $table) {
            $table->string('district_code')->nullable();
            $table->string('sub_district_code')->nullable();
            $table->string('ref_date')->change();
            $table->dropForeign(['district_id']);
            $table->dropForeign(['sub_district_id']);
            $table->dropColumn('district_id');
            $table->dropColumn('sub_district_id');
        });
    }
}
