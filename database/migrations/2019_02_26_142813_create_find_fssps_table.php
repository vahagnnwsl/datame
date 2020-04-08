<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindFsspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_fssps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->string('fio');
            $table->string('number');
            $table->decimal('amount', 20, 2);
            $table->text('nazn');
            $table->text('name_poluch');
            $table->string('bik');
            $table->string('rs');
            $table->string('bank');
            $table->string('kpp');
            $table->string('inn');
            $table->date('date_protocol');
            $table->string('contact');
            $table->string('error_message')->nullable();
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
        Schema::dropIfExists('find_fssps');
    }
}
