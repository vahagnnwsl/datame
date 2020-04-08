<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPassports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->boolean('passport_serie_year')->nullable()->after('passport_date_replace');
            $table->boolean('passport_serie_region')->nullable()->after('passport_serie_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropColumn('passport_serie_year');
            $table->dropColumn('passport_serie_region');
        });
    }
}
