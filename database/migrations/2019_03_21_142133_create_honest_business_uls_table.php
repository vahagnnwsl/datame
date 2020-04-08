<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHonestBusinessUlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('honest_business_uls', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('find_inn_id');

            $table->string('business_id')->nullable();
            $table->string('tip_document')->nullable();
            $table->string('naim_ul_sokr')->nullable();
            $table->string('naim_ul_poln')->nullable();
            $table->string('activnost')->nullable();
            $table->string('inn')->nullable();
            $table->string('kpp')->nullable();
            $table->date('obr_data')->nullable();
            $table->string('adres')->nullable();
            $table->string('kod_okved')->nullable();
            $table->string('naim_okved')->nullable();
            $table->string('rukovoditel')->nullable();

            $table->string('identity');

            $table->timestamps();

            $table->foreign('find_inn_id')->references('id')->on('find_inns')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('honest_business_uls');
    }
}
