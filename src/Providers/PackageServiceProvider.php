<?php

namespace V1nk0\LaravelShopifyRest\Providers;

use Illuminate\Support\ServiceProvider;
use V1nk0\LaravelShopifyRest\Shopify;

class PackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind('shopify_rest',function(){
            return new Shopify();
        });
    }
}
