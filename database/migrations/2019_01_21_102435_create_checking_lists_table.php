<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checking_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->integer('type');
            //0 - новый
            //1 - проверка проведена успешно
            //3 - проверка завершилась ошибкой
            $table->integer('status')->default(0);
            $table->text('message')->nullable();
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
        Schema::dropIfExists('checking_lists');
    }
}
