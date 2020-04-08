<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debtors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('find_inn_id');
            $table->string("result")->nullable();
            $table->string("category")->nullable();
            $table->string("inn")->nullable();
            $table->string("ogrnip")->nullable();
            $table->string("snils")->nullable();
            $table->string("region")->nullable();
            $table->string("live_address")->nullable();
            $table->text("error_message")->nullable();
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
        Schema::dropIfExists('debtors');
    }
}
