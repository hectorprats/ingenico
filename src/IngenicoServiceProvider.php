<?php

namespace Asanzred\Ingenico;

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
            __DIR__.'/../config/ingenico.php' => config_path('ingenico.php'),
        ]);
        $this->loadViewsFrom(__DIR__. '/views/', 'asanzred/ingenico');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIngenico();
        //register out controller
        $this->app->make('Asanzred\Ingenico\Http\Controllers\IngenicoController');
        config([
            'config/ingenico.php', // add your new config file here!
        ]);

    }

    private function registerIngenico()
    {
        $this->app->bind('ingenico',function($app){
            return new Ingenico($app);
        });
    }
}
