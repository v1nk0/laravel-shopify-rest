<?php

namespace V1nk0\LaravelShopifyRest;

use Exception;
use Illuminate\Support\Facades\Http;

class Api
{
    private ?string $token;

    public string $domain;

    public string $version;

    public array $allowedHttpMethods = ['GET', 'POST', 'DELETE'];

    public function __construct(?string $domain, ?string $token){
        $this->domain = $domain ?? config('services.shopify.domain');
        $this->token = $token ?? config('services.shopify.token');
        $this->version = config('services.shopify.version');
    }

    /** @throws Exception */
    public function request(string $method, string $path, array $payload = []): Response
    {
        if(!in_array($method, $this->allowedHttpMethods)) {
            throw new Exception('HTTP-method ' . $method . ' is not allowed');
        }

        if(!$this->domain) {
            throw new Exception('Domain missing');
        }

        if(!$this->token) {
            throw new Exception('Token missing');
        }

        if(!str_starts_with($path, '/admin/') || !strstr($path, '.json')) {
            throw new Exception('Given path is an invalid REST-API path');
        }

        try {
            $httpClient = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->token,
            ]);

            if($payload && $method !== 'GET') {
                $httpClient->withBody(json_encode($payload), 'application/json');
            }

            $response = $httpClient->send($method, $this->_getPath($path, $method, $payload));

            if(!$response->ok()) {
                $errors = $response->json('errors');
                if(is_array($errors)) {
                    $errors = implode(', ', $errors);
                }
                return new Response($response->status(), $response->headers(), [], $errors);
            }

            return new Response($response->status(), $response->headers(), $response->json());
        }
        catch(Exception $e) {
            return new Response(false, [], [], $e->getMessage());
        }
    }

    private function _getPath(string $path, string $method, array $payload = []): string
    {
        // If a full rest-api path is provided, we do not need to alter it
        if(str_starts_with($path, '/admin/api/20')) {
            return $path;
        }

        $url = 'https://'.$this->_getDomain().'/admin/api/'.$this->version.'/'.explode('/admin/', $path)[1];

        if($method !== 'GET' || !$payload) {
            return $url;
        }

        return $url.'?'.http_build_query($payload);
    }

    private function _getDomain(): string
    {
        return str_replace(['http://', 'https://'], '', $this->domain);
    }
}
