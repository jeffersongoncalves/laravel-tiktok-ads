<?php

namespace JeffersonGoncalves\TiktokAds;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class TiktokAds
{
    public function __construct(
        protected ?string $accessToken,
        protected ?string $advertiserId,
        protected string $apiVersion,
    ) {}

    public function advertiserInfo(?string $advertiserId = null): Response
    {
        return $this->get('/advertiser/info/', [
            'advertiser_ids' => json_encode([$this->resolveAdvertiserId($advertiserId)]),
        ]);
    }

    public function campaigns(?string $advertiserId = null, int $page = 1, int $pageSize = 20): Response
    {
        return $this->get('/campaign/get/', [
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
            'page' => $page,
            'page_size' => $pageSize,
        ]);
    }

    public function createCampaign(
        string $name,
        string $objective,
        string $budgetMode = 'BUDGET_MODE_DAY',
        ?float $budget = null,
        ?string $advertiserId = null,
    ): Response {
        return $this->post('/campaign/create/', array_filter([
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
            'campaign_name' => $name,
            'objective_type' => $objective,
            'budget_mode' => $budgetMode,
            'budget' => $budget,
        ], fn ($value) => $value !== null));
    }

    /**
     * @param  array<int, string>  $campaignIds
     */
    public function updateCampaignStatus(array $campaignIds, string $status, ?string $advertiserId = null): Response
    {
        return $this->post('/campaign/status/update/', [
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
            'campaign_ids' => $campaignIds,
            'opt_status' => $status,
        ]);
    }

    public function adGroups(?string $campaignId = null, ?string $advertiserId = null): Response
    {
        return $this->get('/adgroup/get/', array_filter([
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
            'campaign_ids' => $campaignId ? json_encode([$campaignId]) : null,
        ], fn ($value) => $value !== null));
    }

    /**
     * @param  array<int, string>  $dimensions
     * @param  array<int, string>  $metrics
     */
    public function report(
        string $startDate,
        string $endDate,
        array $dimensions = ['campaign_id'],
        array $metrics = ['spend', 'impressions', 'clicks', 'conversion'],
        string $dataLevel = 'AUCTION_CAMPAIGN',
        string $reportType = 'BASIC',
        ?string $advertiserId = null,
    ): Response {
        return $this->post('/report/integrated/get/', [
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
            'report_type' => $reportType,
            'dimensions' => $dimensions,
            'metrics' => $metrics,
            'data_level' => $dataLevel,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    public function audiences(?string $advertiserId = null): Response
    {
        return $this->get('/dmp/custom_audience/list/', [
            'advertiser_id' => $this->resolveAdvertiserId($advertiserId),
        ]);
    }

    protected function resolveAdvertiserId(?string $advertiserId): string
    {
        $advertiserId ??= $this->advertiserId;

        throw_if(blank($advertiserId), new InvalidArgumentException('advertiser_id required (pass explicitly or set TIKTOK_ADVERTISER_ID)'));

        return $advertiserId;
    }

    /**
     * @param  array<string, mixed>  $query
     */
    protected function get(string $path, array $query = []): Response
    {
        return $this->client()->get($path, $query);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function post(string $path, array $data = []): Response
    {
        return $this->client()->post($path, $data);
    }

    protected function client(): PendingRequest
    {
        return Http::baseUrl("https://business-api.tiktok.com/open_api/{$this->apiVersion}")
            ->withHeaders(['Access-Token' => $this->accessToken])
            ->asJson();
    }
}
