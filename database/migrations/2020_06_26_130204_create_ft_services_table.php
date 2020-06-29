<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFtServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ft_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inn_id');
            $table->foreign('inn_id')->references('id')->on('find_inns')->onDelete('cascade');;
            $table->string('status')->nullable();
            $table->string('message')->nullable();

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
        Schema::dropIfExists('ft_services');
    }
}
