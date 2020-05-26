<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFindFisnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_fsins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->text("result")->nullable();
            $table->string("full_name")->nullable();
            $table->string("territorial_authorities")->nullable();
            $table->string("federal_authorities")->nullable();
            $table->text("error_message")->nullable();
            $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('find_fisns');
    }
}
