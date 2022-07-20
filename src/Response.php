<?php

namespace V1nk0\LaravelShopifyRest;

class Response
{
    public function __construct(
        public array $payload = []
    ){}
}