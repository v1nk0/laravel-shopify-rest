<?php

namespace V1nk0\LaravelShopifyRest;

class Response
{
    /**
     * @param bool $success
     * @param array $payload
     * @param string|null $error
     * @param Link[] $links
     */
    public function __construct(
        public bool $success = true,
        public array $payload = [],
        public ?string $error = null,
        public array $links = [],
    ){}

    public function setLink(Link $link)
    {
        $this->links[$link->rel] = $link;
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
