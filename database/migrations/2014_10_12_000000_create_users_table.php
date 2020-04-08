<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->boolean("confirmed")->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            //1 - admin
            //2 - физ. лицо
            //3 - юр. лицо
            $table->smallInteger('type_user')->default(2);
            $table->bigInteger('phone');
            $table->string('inn')->nullable();
            $table->string('ogrn')->nullable();
            $table->string('director')->nullable();
            $table->string('manager')->nullable();
            $table->timestamp('date_service')->nullable();
            $table->integer('check_quantity')->default(0);
            $table->string('ip')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
