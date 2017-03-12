<?php

namespace Laravel\Tests\Forge\Helpers;

use Mockery;
use Closure;
use GuzzleHttp\Client;
use Laravel\Forge\ApiProvider;

class Api
{
    /**
     * Creates fake API Provider.
     *
     * @param \Closure $callback
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public static function fake(Closure $callback)
    {
        $api = Mockery::mock(ApiProvider::class.'[getClient]', ['api-token']);
        $http = Mockery::mock(Client::class);

        $callback($http);

        $api->shouldReceive('getClient')->andReturn($http);

        return $api;
    }

    /**
     * Generates server data.
     *
     * @param array $replace = []
     *
     * @return array
     */
    public static function serverData(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'credential_id' => 1,
            'name' => 'northrend',
            'size' => '512MB',
            'region' => 'Amsterdam 2',
            'php_version' => 'php71',
            'ip_address' => '37.139.3.148',
            'private_ip_address' => '10.129.3.252',
            'blackfire_status' => null,
            'papertail_status' => null,
            'revoked' => false,
            'created_at' => '2016-12-15 18:38:18',
            'is_ready' => true,
            'network' => [],
        ], $replace);
    }
}