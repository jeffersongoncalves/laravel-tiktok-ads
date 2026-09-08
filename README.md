![Laravel TikTok Ads](art/jeffersongoncalves-laravel-tiktok-ads.png)

# Laravel TikTok Ads

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-tiktok-ads.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-tiktok-ads)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-tiktok-ads.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-tiktok-ads)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-tiktok-ads.svg?style=flat-square)](LICENSE.md)

Laravel integration for the TikTok Ads (TikTok Business) API — advertiser info, campaigns, ad groups, integrated reports and custom audiences.

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-tiktok-ads
```

Publish the config file:

```bash
php artisan vendor:publish --tag="laravel-tiktok-ads-config"
```

Set your credentials in `.env`:

```env
TIKTOK_ACCESS_TOKEN=
TIKTOK_ADVERTISER_ID=
TIKTOK_ADS_API_VERSION=v1.3
```

Get an access token from the [TikTok for Business developer portal](https://business-api.tiktok.com/portal).

## Usage

```php
use JeffersonGoncalves\TiktokAds\Facades\TiktokAds;

TiktokAds::advertiserInfo(); // uses TIKTOK_ADVERTISER_ID by default
TiktokAds::advertiserInfo('7000000000000000000'); // or pass an advertiser id explicitly

TiktokAds::campaigns();
TiktokAds::campaigns(page: 2, pageSize: 50);

TiktokAds::createCampaign(name: 'My Campaign', objective: 'TRAFFIC', budgetMode: 'BUDGET_MODE_DAY', budget: 50.0);

TiktokAds::updateCampaignStatus(campaignIds: ['1790000000000000000'], status: 'DISABLE');

TiktokAds::adGroups();
TiktokAds::adGroups(campaignId: '1790000000000000000');

TiktokAds::report(
    startDate: '2026-01-01',
    endDate: '2026-01-31',
    dimensions: ['campaign_id'],
    metrics: ['spend', 'impressions', 'clicks', 'conversion'],
    dataLevel: 'AUCTION_CAMPAIGN',
);

TiktokAds::audiences();
```

Every method returns an `Illuminate\Http\Client\Response`, so you can chain `->json()`, `->throw()`, etc. Note that the TikTok Business API answers with HTTP 200 even on failure — check the `code` field in the payload:

```php
$response = TiktokAds::campaigns();

if ($response->json('code') !== 0) {
    report(new RuntimeException($response->json('message')));
}
```

You can also resolve the client from the container instead of using the facade:

```php
app(JeffersonGoncalves\TiktokAds\TiktokAds::class)->campaigns();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email the author instead of using the issue tracker.

## Credits

- [jeffersongoncalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
