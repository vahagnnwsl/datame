<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        DB::listen(function($query) {
//            ApiLog::staticInstance()->info("query", [
//                $query->sql,
//                $query->bindings,
//                $query->time
//            ]);
//            dump([
//                $query->sql,
//                $query->bindings,
//                $query->time
//            ]);
        });
    }
}
