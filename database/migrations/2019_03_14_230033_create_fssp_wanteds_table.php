<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFsspWantedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fssp_wanteds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->string("result")->nullable();
//            Место рождения подозреваемого
            $table->string("city_birth")->nullable();
//            Наименование органа инициировавшего розыск
            $table->string("name_organ")->nullable();
//            Контактная информация гос. органа, инициировавшего розыск
            $table->string("contact_name_organ")->nullable();
//            Наименование гос. органа, осуществляющего розыск
            $table->string("name_organ_wanted")->nullable();
//            Статья УК РФ, по которой возбуждено дело
            $table->string("article_deal")->nullable();

            $table->text("error_message")->nullable();
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
        Schema::dropIfExists('fssp_wanteds');
    }
}
