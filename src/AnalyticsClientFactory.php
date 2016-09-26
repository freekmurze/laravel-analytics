<?php

namespace Spatie\Analytics;

use Stash;
use Google_Client;
use Google_Service_Analytics;
use Illuminate\Contracts\Cache\Repository;

class AnalyticsClientFactory
{
    public static function createForConfig(array $analyticsConfig): AnalyticsClient
    {
        $authenticatedClient = self::createAuthenticatedGoogleClient($analyticsConfig);

        $googleService = new Google_Service_Analytics($authenticatedClient);

        return self::createAnalyticsClient($analyticsConfig, $googleService);
    }

    public static function createAuthenticatedGoogleClient(array $config): Google_Client
    {
        $client = new Google_Client();

        $client->setScopes([
            Google_Service_Analytics::ANALYTICS_READONLY
        ]);

        $client->setAuthConfig($config['service_account_credentials_json']);

        $path = $config['cache_location'] ?? storage_path('app/laravel-google-analytics/google-cache/');

        $driver = new Stash\Driver\FileSystem(['path' => $path]);

        $client->setCache(new Stash\Pool($driver));

        return $client;
    }

    protected static function createAnalyticsClient(array $analyticsConfig, Google_Service_Analytics $googleService): AnalyticsClient
    {
        $client = new AnalyticsClient($googleService, app(Repository::class));

        $client->setCacheLifeTimeInMinutes($analyticsConfig['cache_lifetime_in_minutes']);

        return $client;
    }
}
