<?php

namespace App\Providers;
// imports 
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Support\ServiceProvider;
//import this
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        Schema::defaultStringLength(191);
        // code in `register` method 
Event::listen(MigrationsStarted::class, function (){
    if (env('ALLOW_DISABLED_PK')) {
        DB::statement('SET SESSION sql_require_primary_key=0');
    }
});

        Event::listen(MigrationsEnded::class, function (){
            if (env('ALLOW_DISABLED_PK')) {
                DB::statement('SET SESSION sql_require_primary_key=1');
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadViewsFrom(__DIR__.'/../resources/views/vendor/mail/html', 'courier');
    }
}