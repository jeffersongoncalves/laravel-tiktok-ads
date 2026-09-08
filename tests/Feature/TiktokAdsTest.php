<?php

use Illuminate\Support\Facades\Http;
use JeffersonGoncalves\TiktokAds\Facades\TiktokAds;

beforeEach(function () {
    config(['laravel-tiktok-ads.access_token' => 'test-token']);
    config(['laravel-tiktok-ads.advertiser_id' => '123']);
});

it('fetches advertiser info with the default advertiser id', function () {
    Http::fake([
        'business-api.tiktok.com/*' => Http::response(['code' => 0, 'data' => ['list' => [['advertiser_id' => '123']]]]),
    ]);

    $response = TiktokAds::advertiserInfo();

    expect($response->json('data.list.0.advertiser_id'))->toBe('123');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/open_api/v1.3/advertiser/info/')
        && str_contains(urldecode((string) $request->url()), '["123"]')
        && $request->header('Access-Token') === ['test-token']);
});

it('lists campaigns for an explicit advertiser id', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0, 'data' => ['list' => []]])]);

    TiktokAds::campaigns('999');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), 'advertiser_id=999')
        && str_contains((string) $request->url(), 'page_size=20'));
});

it('throws when no advertiser id is available', function () {
    config(['laravel-tiktok-ads.advertiser_id' => null]);

    TiktokAds::campaigns();
})->throws(InvalidArgumentException::class);

it('creates a campaign and omits a null budget', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0, 'data' => ['campaign_id' => '456']])]);

    $response = TiktokAds::createCampaign('My Campaign', 'TRAFFIC');

    expect($response->json('data.campaign_id'))->toBe('456');

    Http::assertSent(fn ($request) => $request['campaign_name'] === 'My Campaign'
        && $request['objective_type'] === 'TRAFFIC'
        && $request['budget_mode'] === 'BUDGET_MODE_DAY'
        && ! array_key_exists('budget', $request->data()));
});

it('updates campaign status for many ids', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0])]);

    TiktokAds::updateCampaignStatus(['1', '2'], 'DISABLE');

    Http::assertSent(fn ($request) => $request['campaign_ids'] === ['1', '2'] && $request['opt_status'] === 'DISABLE');
});

it('filters ad groups by campaign id', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0])]);

    TiktokAds::adGroups('789');

    Http::assertSent(fn ($request) => str_contains(urldecode((string) $request->url()), 'campaign_ids=["789"]'));
});

it('runs an integrated report with default dimensions and metrics', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0, 'data' => ['list' => []]])]);

    TiktokAds::report('2026-01-01', '2026-01-31');

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/report/integrated/get/')
        && $request['dimensions'] === ['campaign_id']
        && $request['metrics'] === ['spend', 'impressions', 'clicks', 'conversion']
        && $request['data_level'] === 'AUCTION_CAMPAIGN'
        && $request['start_date'] === '2026-01-01'
        && $request['end_date'] === '2026-01-31');
});

it('lists custom audiences', function () {
    Http::fake(['business-api.tiktok.com/*' => Http::response(['code' => 0])]);

    TiktokAds::audiences();

    Http::assertSent(fn ($request) => str_contains((string) $request->url(), '/dmp/custom_audience/list/'));
});
