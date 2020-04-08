<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIdentity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('find_inns', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('find_taxes', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('find_fssps', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('interpol_reds', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('interpol_yellows', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('fed_fsms', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('mvd_wanteds', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('fssp_wanteds', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('disqs', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('debtors', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('honest_business_uls', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('honest_business_ips', function (Blueprint $table) {
            $table->dropColumn('identity');
        });

        Schema::table('find_departments', function (Blueprint $table) {
            $table->dropColumn('identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('find_inns', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('passports', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('find_taxes', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('find_fssps', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('interpol_reds', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('interpol_yellows', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('fed_fsms', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('mvd_wanteds', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('fssp_wanteds', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('disqs', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('debtors', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('honest_business_uls', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('honest_business_ips', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });

        Schema::table('find_departments', function (Blueprint $table) {
            $table->string('identity')->nullable();
        });
    }
}
