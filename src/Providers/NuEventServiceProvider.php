<?php

namespace Numesia\NuEvent\Providers;

use Numesia\NuEvent\NuEvent;
use Illuminate\Support\ServiceProvider;

class NuEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('nuevent.php'),
        ], 'config');

        $routeConfig = [
            'namespace'  => 'Numesia\NuEvent\Controllers',
            'prefix'     => '__nuevent',
        ];

        if (!$this->app->routesAreCached()) {
            $this->app['router']->group($routeConfig, function ($router) {
                $router->post('/', 'EventController@createInternalEvent');
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('NuEvent', function () {
            return new NuEvent;
        });
    }
}
