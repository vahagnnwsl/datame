<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("checking_state")->default(1);
            $table->unsignedInteger('app_id');
            $table->boolean('is_valid')->nullable();
            $table->string('status')->nullable();
            $table->date('age14')->nullable();
            $table->date('age20')->nullable();
            $table->date('age45')->nullable();
            $table->date('passport_date_replace')->nullable();
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
        Schema::dropIfExists('passports');
    }
}
