<?php

namespace Jeffersongoncalves\TiktokAds\Tests;

use Jeffersongoncalves\TiktokAds\TiktokAdsServiceProvider;
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
