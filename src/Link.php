<?php

namespace V1nk0\LaravelShopifyRest;

class Link
{
    public function __construct(
        public string $url,
        public string $rel,
    ){}

    public function getQueryParam(string $param): string|int|null
    {
        $parts = parse_url($this->url);
        parse_str($parts['query'], $queryParams);

        return $queryParams[$param] ?? null;
    }
}
