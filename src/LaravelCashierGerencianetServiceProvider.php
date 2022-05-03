<?php

namespace AgenciaMav\LaravelCashierGerencianet;

use AgenciaMav\LaravelCashierGerencianet\Http\Controllers\Subscription;
use AgenciaMav\LaravelCashierGerencianet\Http\Controllers\Charge;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelCashierGerencianetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-cashier-gerencianet');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-cashier-gerencianet');
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');


        if ($this->app->runningInConsole()) {
            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-cashier-gerencianet'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);

            // Publishing configs.
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('cashier-gerencianet.php'),
            ], 'laravel-cashier-gerencianet-config');

            // Publish assets
            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('laravel-cashier-gerencianet'),
            ], 'laravel-cashier-gerencianet-assets');

            // Publish views
            // $this->publishes([
            //     __DIR__ . '/../resources/views' => resource_path('views/vendor/' . config('laravel-cashier-gerencianet.prefix_base', 'laravel-cashier-gerencianet')),
            // ], 'laravel-cashier-gerencianet-views');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-cashier-gerencianet');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-cashier-gerencianet', function () {
            return new LaravelCashierGerencianet();
        });

        $this->app->bind('subscription', function ($app) {
            return new Subscription();
        });
        $this->app->bind('charge', function ($app) {
            return new Charge();
        });
    }
}
