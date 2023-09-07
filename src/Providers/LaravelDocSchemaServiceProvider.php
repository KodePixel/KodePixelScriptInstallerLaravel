<?php

namespace Alex\LaravelDocSchema\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Alex\LaravelDocSchema\Middleware\canInstall;
use Alex\LaravelDocSchema\Middleware\canUpdate;
use Alex\LaravelDocSchema\Middleware\PdocsMiddleware;
use Illuminate\Support\Facades\DB;
use Schema;


class LaravelDocSchemaServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->publishFiles();
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Bootstrap the application events.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {

        $router->middlewareGroup('', [PdocsMiddleware::class]); 
        $router->middlewareGroup('install', [CanInstall::class]);
        $router->middlewareGroup('update', [CanUpdate::class]);
 
    }

    /**
     * Publish config file for the requirements.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'pdo');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/requirements.php','requirements'
        );
        $this->publishes([ 
            __DIR__.'/../assets' => public_path(strDec('aW5zdGFsbGVy')),
        ], strDec('bGFyYXZlbGluc3RhbGxlcg=='));

        $this->publishes([
            __DIR__.'/../Lang' => base_path('resources/lang'),
        ], strDec('bGFyYXZlbGluc3RhbGxlcg=='));
    }
}
