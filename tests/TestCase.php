<?php

namespace AksService\DocumentWrapper\Tests;

use \AksService\DocumentWrapper\DocumentWrapperServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app): array
    {
        return [
            DocumentWrapperServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    /**
     * Ignore package discovery from.
     *
     * @return array
     */
    public function ignorePackageDiscoveriesFrom(): array
    {
        return ['laravel/passport'];
    }

}
