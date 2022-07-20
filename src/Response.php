<?php

namespace V1nk0\LaravelShopifyRest;

class Response
{
    /** @var Link[] */
    public array $links;

    public bool $success = false;

    public function __construct(
        public ?int $statusCode = null,
        public array $headers = [],
        public array $payload = [],
        public ?string $error = null,
    ){
        if($this->statusCode === 200) {
            $this->success = true;
        }

        if(isset($this->headers['Link'])) {
            $links = explode(',', $this->headers['Link']);
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
