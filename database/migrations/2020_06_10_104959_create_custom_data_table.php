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
            $table->date('birthday')->index();
            $table->text('additional')->nullable();

            $table->unique(['full_name', 'birthday']);
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
