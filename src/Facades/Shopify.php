<?php

namespace V1nk0\LaravelShopifyRest\Facades;

use Exception;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array|null request(string $method, string $path, array $payload = []) {
 *   @throws Exception
 * }
 * @method static void setToken(string $token)
 */

class Shopify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopify.rest';
    }
}
