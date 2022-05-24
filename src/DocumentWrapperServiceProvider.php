<?php

namespace AksService\DocumentWrapper;

use Illuminate\Support\ServiceProvider;

class DocumentWrapperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('document', function($app) {
            return new Document();
        });
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'document');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'document');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('document.php'),
            ], 'config');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/documentwrapper'),
            ], 'views');

        }
    }
}
