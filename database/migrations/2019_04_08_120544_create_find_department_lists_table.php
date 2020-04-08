<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindDepartmentListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_department_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('find_department_id');
            $table->unsignedInteger('department_id');
            $table->timestamps();

            $table->foreign('find_department_id')->references('id')->on('find_departments')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('find_department_lists');
    }
}
