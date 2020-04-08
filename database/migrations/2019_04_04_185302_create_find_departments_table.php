<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->integer('type')->default(0);
            $table->text("error_message")->nullable();
            $table->unsignedInteger('selected_department_id')->nullable();
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
        Schema::dropIfExists('find_departments');
    }
}
