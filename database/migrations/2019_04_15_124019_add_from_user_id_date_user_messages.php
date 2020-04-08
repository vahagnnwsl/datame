<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromUserIdDateUserMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('user_messages', 'from_user_id')) {
                $table->unsignedInteger('from_user_id')->after('id');
            }
            $table->renameColumn('user_id', 'to_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_messages', function (Blueprint $table) {
            $table->dropColumn('from_user_id');
            $table->renameColumn('to_user_id', 'user_id');
        });
    }
}
