<?php

namespace JeffersonGoncalves\TiktokAds\Tests;

use JeffersonGoncalves\TiktokAds\TiktokAdsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TiktokAdsServiceProvider::class,
        ];
    }
}
