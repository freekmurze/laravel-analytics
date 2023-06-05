<?php

namespace Spatie\Analytics\Tests\TestSupport;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Analytics\AnalyticsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AnalyticsServiceProvider::class,
        ];
    }
}
