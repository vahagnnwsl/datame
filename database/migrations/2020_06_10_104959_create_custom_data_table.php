<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name')->index();
            $table->timestamp('birthday')->index();
            $table->string('passport')->index();
            $table->timestamp('passport_date')->index();
            $table->json('additional')->nullable();

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
        Schema::dropIfExists('custom_data');
    }
}
