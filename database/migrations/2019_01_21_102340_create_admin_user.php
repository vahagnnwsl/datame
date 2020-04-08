<?php

use App\User;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Artisan::call('passport:install');

        User::create([
            'name' => "admin",
            'email' => "admin@gmail.com",
            'lastname' => "admin",
            'phone' => add_seven_to_phone("9855353135"),
            'type_user' => 1,
            'password' => Hash::make('$adminu44871')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        User::where('email', 'admin@gmail.com')->delete();
    }
}
