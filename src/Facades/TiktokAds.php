<?php

namespace Jeffersongoncalves\TiktokAds\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jeffersongoncalves\TiktokAds\TiktokAds
 */
class TiktokAds extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-tiktok-ads';
    }
}
