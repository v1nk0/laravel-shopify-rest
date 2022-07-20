<?php

namespace V1nk0\LaravelShopifyRest;

use Exception;
use Illuminate\Support\Facades\Http;

class Api
{
    private ?string $token;

    public string $domain;

    public string $version;

    public array $allowedHttpMethods = ['GET', 'POST'];

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

            if($payload) {
                $httpClient->withBody(json_encode($payload), 'application/json');
            }

            $response = $httpClient->send($method, $this->_getPath($path));

            $response->throw();

            return new Response($response->json());
        }
        catch(Exception $e) {
            // Do something?
            throw new Exception($e->getMessage());
        }
    }

    public function setToken(string $token): Api
    {
        $this->token = $token;
        return $this;
    }

    private function _getPath(string $path): string
    {
        // If a full rest-api path is provided, we do not need to alter it
        if(str_starts_with($path, '/admin/api/20')) {
            return $path;
        }

        return 'https://'.$this->_getDomain().'/admin/api/'.$this->version.'/'.explode('/admin/', $path)[1];
    }

    private function _getDomain(): string
    {
        return str_replace(['http://', 'https://'], '', $this->domain);
    }
}