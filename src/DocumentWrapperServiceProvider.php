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
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
