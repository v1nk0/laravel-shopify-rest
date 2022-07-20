<?php

namespace V1nk0\LaravelShopifyRest;

use V1nk0\LaravelShopifyRest\Facades\Shopify;

class Response
{
    /** @var Link[] */
    public array $links = [];

    public bool $success = false;

    public function __construct(
        public ?int $statusCode = null,
        public array $headers = [],
        public array $payload = [],
        public ?string $error = null,
    ){
        if($this->statusCode && $this->statusCode >= 200 && $this->statusCode <= 206) {
            $this->success = true;
        }

        if($this->statusCode === 419) {
            $retryAfter = $this->getHeader('Retry-After') ?? config('services.shopify.retry_after', 60);
            Shopify::setRetryAfter((int)$retryAfter);
        }

        if(isset($this->headers['Link'][0])) {
            $links = explode(',', $this->headers['Link'][0]);
            foreach($links as $link) {
                $link = trim($link);
                $parts = explode(';', $link);
                $url = trim(str_replace(['<', '>'], '', $parts[0]));
                $rel = trim(str_replace(['rel=', '"'], '', $parts[1]));

                $this->links[] = new Link($url, $rel);
            }
        }
    }

    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    public function hasHeader(string $key): bool
    {
        return (isset($this->headers[$key][0]));
    }

    public function getHeader(string $key): int|string|null
    {
        if(!$this->hasHeader($key)) {
            return null;
        }

        return $this->headers[$key][0];
    }

    public function hasLinkWithRel(string $rel): bool
    {
        foreach($this->links as $link) {
            if($link->rel === $rel) {
                return true;
            }
        }

        return false;
    }

    public function getLinkWithRel(string $rel): ?Link
    {
        foreach($this->links as $link) {
            if($link->rel === $rel) {
                return $link;
            }
        }

        return null;
    }
}
