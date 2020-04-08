<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('find_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('find_inn_id');
            $table->text('article');
            $table->string('number');
            $table->date('date_protocol');
            $table->decimal('amount', 20, 2);
            $table->string('name');
            $table->string('inn');
            $table->string('kpp');
            $table->string('okato');
            $table->string('bik');
            $table->string('rs');
            $table->string('kbk');
            $table->string('identity');

            $table->foreign('find_inn_id')->references('id')->on('find_inns')->onDelete('cascade');
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
        Schema::dropIfExists('find_taxes');
    }
}
