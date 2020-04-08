<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{

    private $table = "apps";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->string('lastname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->date('birthday');
            $table->string('passport_code')->nullable();;
            $table->date('date_of_issue')->nullable();;
            $table->string('code_department')->nullable();
            $table->unsignedInteger('current_age')->nullable();
            $table->boolean('passport')->nullable();
            $table->boolean('inn')->nullable();
            $table->boolean('tax')->nullable();
            // 1 - новая заявка
            // 2 - в обработке
            // 3 - ошибка
            // 4 - финальный статус, все проверки проведены успешно.
            $table->integer('status')->default(1);
            $table->integer("checking_count")->default(0);
            //максимальное кол-во повторов перезапуска проверки заявки пользователем
            $table->integer('checking_repeat')->default(0);
            $table->dateTime("checking_date_next")->nullable();
            //нужно ли вернуть количество если заявка обработана с ошибкой
            $table->integer("return_check_quantity")->default(0);
            $table->string('ip')->nullable();
            $table->string('identity');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
