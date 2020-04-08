<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFedFsmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fed_fsms', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('app_id');
            $table->string("status")->nullable();
            $table->string("full_name")->nullable();
            $table->string("city_birth")->nullable();
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
        Schema::dropIfExists('fed_fsms');
    }
}
