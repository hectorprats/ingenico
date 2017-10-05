<?php

namespace Bardela\Ingenico;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class IngenicoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/ingenico.php' => config_path('ingenico.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //register out controller
        $this->app->make('Bardela\Ingenico\IngenicoController');
        config([
            'config/ingenico.php', // add your new config file here!
        ]);

    }

}
