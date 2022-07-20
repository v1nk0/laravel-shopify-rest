<?php

namespace V1nk0\LaravelShopifyRest\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool rateLimitingActive()
 * @method static int getRetryAfter()
 * @method static int setRetryAfter(int $seconds)
 */

class Shopify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopify_rest';
    }
}