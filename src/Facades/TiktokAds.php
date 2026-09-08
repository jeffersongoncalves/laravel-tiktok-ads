<?php

namespace JeffersonGoncalves\TiktokAds\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\TiktokAds\TiktokAds
 */
class TiktokAds extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\TiktokAds\TiktokAds::class;
    }
}
