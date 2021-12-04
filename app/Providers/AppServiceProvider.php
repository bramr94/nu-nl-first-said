<?php

namespace App\Providers;

use App\Helpers\Crawler;
use App\Helpers\Feed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('crawler', static function () {
            return new Crawler();
        });
        $this->app->singleton('feed', static function () {
            return new Feed();
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
    }
}
