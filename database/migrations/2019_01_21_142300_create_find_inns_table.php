<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindInnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_inns', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->string("inn")->nullable();
            $table->integer("type_inn");
            $table->text("error_message")->nullable();
            $table->string('identity');
            $table->timestamps();

            $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('find_inns');
    }
}
