<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHonestBusinessIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('honest_business_ips', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('find_inn_id');


            $table->string('business_id')->nullable();
            $table->string('tip_document')->nullable();
            $table->string('naim_vid_ip')->nullable();
            $table->string('familia')->nullable();
            $table->string('imia')->nullable();
            $table->string('otchestvo')->nullable();
            $table->string('activnost')->nullable();
            $table->string('innfl')->nullable();
            $table->date('data_ogrnip')->nullable();
            $table->string('naim_stran')->nullable();
            $table->string('kod_okved')->nullable();
            $table->string('naim_okved')->nullable();

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
        Schema::dropIfExists('honest_business_ips');
    }
}
