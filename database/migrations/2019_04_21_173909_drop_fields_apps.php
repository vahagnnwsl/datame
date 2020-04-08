<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFieldsApps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apps', function (Blueprint $table) {
           $table->dropColumn('current_age');
           $table->dropColumn('passport');
           $table->dropColumn('inn');
           $table->dropColumn('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->unsignedInteger('current_age')->nullable();
            $table->boolean('passport')->nullable();
            $table->boolean('inn')->nullable();
            $table->boolean('tax')->nullable();
        });
    }
}
