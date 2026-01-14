<?php

namespace NinePay;

use Illuminate\Support\ServiceProvider;

class NinePayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ninepay.php', 'ninepay');

        $this->app->singleton('ninepay', function ($app) {
            $config = $app['config']->get('ninepay');
            return new PaymentManager($config);
        });

        $this->app->alias('ninepay', PaymentManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ninepay.php' => base_path('config/ninepay.php'),
            ], 'ninepay-config');
        }
    }
}
