<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disqs', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->string("result")->nullable();
//            Дата истечения срока дисквалификации
            $table->date("data_con_discv")->nullable();
//            Наименование органа, составившего протокол об административном правонарушении
            $table->string("naim_org_prot")->nullable();
//            Должность
            $table->string("dolgnost")->nullable();
//            Наименование организации
            $table->string("naim_org")->nullable();
//           Дата начала дисквалификации
            $table->date("data_nach_discv")->nullable();
//            Должность судьи
            $table->string("dolgnost_sud")->nullable();
//            Место рождения дисквалифицированного лица
            $table->string("mesto_rogd")->nullable();
//            Срок дисквалификации
            $table->string("discv_srok")->nullable();
//            Статья КоАП РФ
            $table->string("kvalivikaciya_tekst")->nullable();
            //Номер записи РДЛ
            $table->string("nom_zap")->nullable();
//            ФИО судьи
            $table->string("fio_sud")->nullable();

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
        Schema::dropIfExists('disqs');
    }
}
