<?php

namespace Spool\ApiInfo;

use Illuminate\Support\ServiceProvider;

class ApiInfoProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__ . '/route/apiinfo.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'ApiInfo');
        $this->app->singleton("ApiInfo", function () {
            return new ApiInfo();
        });
        $this->publishes([
            __DIR__ . '/config/apiinfo.php' => config_path('apiinfo.php'),
            __DIR__ . '/resources/views' => resource_path('views/vendor/apiinfo'),
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/apiinfo'),
            __DIR__ . '/resources/assets/css' => public_path('vendor/apiinfo/css'),
            __DIR__ . '/resources/assets/js' => public_path('vendor/apiinfo/js'),
        ], 'apiinfo');
    }
}
