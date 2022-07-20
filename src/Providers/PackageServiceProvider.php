<?php

namespace V1nk0\LaravelShopifyRest\Providers;

use Illuminate\Support\ServiceProvider;
use V1nk0\LaravelShopifyRest\Api;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('shopify.rest',function(){
            return new Api();
        });
    }
}
