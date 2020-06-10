<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomDataImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_data_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->string('delimiter')->default('|');
            $table->string('short_description')->nullable();
            $table->json('columns_map')->nullable();
            $table->text('error_message')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: new, 1: processing, 2: success, 3: failed');
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
        Schema::dropIfExists('custom_data_imports');
    }
}
