<?php

namespace Jeffersongoncalves\TiktokAds;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TiktokAdsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-tiktok-ads')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations();
    }
}
