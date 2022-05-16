<?php

namespace App\Providers;

use App\Services\SageCloud\SageCloudApiService;
use Illuminate\Support\ServiceProvider;

class PaynacleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SageCloudApiService::class, function ($app){
            return new SageCloudApiService([
                'email' => config('sage-cloud.email'),
                'password' => config('sage-cloud.password')
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
