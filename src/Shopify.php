<?php

namespace V1nk0\LaravelShopifyRest;

use Illuminate\Support\Facades\Cache;

class Shopify
{
    public string $rateLimitingCacheKey;

    public function __construct()
    {
        $this->rateLimitingCacheKey = config('services.shopify.rate_limit_expiry_cache_key', 'shopify-rate-limit-expiry');
    }

    public function rateLimitingActive(): bool
    {
        return (bool)Cache::get($this->rateLimitingCacheKey);
    }

    public function getRetryAfter(): int
    {
        $timestamp = Cache::get($this->rateLimitingCacheKey);

        if(!$timestamp) {
            return 0;
        }

        return $timestamp - time();
    }

    public function setRetryAfter(int $seconds): int
    {
        Cache::put(
            $this->rateLimitingCacheKey,
            now()->addSeconds($seconds)->timestamp,
            $seconds
        );

        return $seconds;
    }
}