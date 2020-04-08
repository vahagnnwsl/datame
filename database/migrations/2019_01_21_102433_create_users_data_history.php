<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDataHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_data_history', function (Blueprint $table) {

            $table->increments('id');
            $table->boolean('confirmed')->default(false);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->bigInteger('phone');
            $table->string('inn')->nullable();
            $table->string('ogrn')->nullable();
            $table->string('director')->nullable();
            $table->string('manager')->nullable();
            $table->date('date_service')->nullable();
            $table->integer('check_quantity')->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_data_history');
    }
}
