<?php

namespace V1nk0\LaravelShopifyRest\Facades;

use Exception;
use Illuminate\Support\Facades\Facade;
use V1nk0\LaravelShopifyRest\Api;

/**
 * @method static array|null request(string $method, string $path, array $payload = []) {
 *   @throws Exception
 * }
 * @method static Api setToken(string $token)
 */

class Shopify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopify.rest';
    }
}
