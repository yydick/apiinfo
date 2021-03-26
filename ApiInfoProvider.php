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
        $this->publishes([
            __DIR__ . '/Config/apiinfo.php' => config_path('apiinfo.php'),
        ], 'apiinfo');
        $this->app->singleton("ApiInfo",function (){
            return new ApiInfo();
        });
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'ApiInfo');
        $this->publishes([
            __DIR__.'/Assets/views' => resource_path('views/vendor/apiinfo'),
        ], 'apiinfo');
        $this->publishes([
            __DIR__.'/Assets/lang' => resource_path('lang/vendor/apiinfo'),
        ], 'apiinfo');
        $this->publishes([
            __DIR__.'/Assets/css' => public_path('vendor/apiinfo/css'),
        ], 'apiinfo');
        $this->publishes([
            __DIR__.'/Assets/js' => public_path('vendor/apiinfo/js'),
        ], 'apiinfo');
    }
}
