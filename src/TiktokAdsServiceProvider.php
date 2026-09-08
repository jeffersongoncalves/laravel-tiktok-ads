<?php

namespace JeffersonGoncalves\TiktokAds;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TiktokAdsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-tiktok-ads')
            ->hasConfigFile('laravel-tiktok-ads');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(TiktokAds::class, fn () => new TiktokAds(
            config('laravel-tiktok-ads.access_token'),
            config('laravel-tiktok-ads.advertiser_id'),
            config('laravel-tiktok-ads.api_version'),
        ));

        $this->app->alias(TiktokAds::class, 'laravel-tiktok-ads');
    }
}
